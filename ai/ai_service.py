import torch
from transformers import GPT2LMHeadModel, GPT2Tokenizer
import psycopg2
import json
import sys
import requests
from bs4 import BeautifulSoup

# Model ve Tokenizer'ı Yükleme
MODEL_PATH = "ai/gpt2-large.safetensors"
tokenizer = GPT2Tokenizer.from_pretrained("gpt2-large")
model = GPT2LMHeadModel.from_pretrained("gpt2-large")
model.load_state_dict(torch.load(MODEL_PATH, map_location=torch.device('cpu')))
model.eval()

# PostgreSQL Bağlantı Bilgileri
DB_CONFIG = {
    "dbname": "rein_db",
    "user": "admin",
    "password": "admin",
    "host": "localhost",
    "port": "5432"
}

def fetch_database_data():
    """Tüm veritabanını JSON formatında çeker ve büyük tabloları böler."""
    conn = psycopg2.connect(**DB_CONFIG)
    cursor = conn.cursor()

    # Tüm tabloları listeleme
    query = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public';"
    cursor.execute(query)
    tables = cursor.fetchall()

    db_data = {}

    for table in tables:
        table_name = table[0]

        # Sütun isimleri ve veri tiplerini alma
        cursor.execute(f"""
        SELECT column_name, data_type
        FROM information_schema.columns
        WHERE table_name = '{table_name}';
        """)
        columns_info = cursor.fetchall()
        columns = {col[0]: col[1] for col in columns_info}

        # Tablo boyutunu öğrenme
        cursor.execute(f"SELECT COUNT(*) FROM {table_name};")
        row_count = cursor.fetchone()[0]

        # Büyük tabloları böl
        data_chunks = []
        chunk_size = 1000

        if row_count > chunk_size:
            for i in range(0, row_count, chunk_size):
                cursor.execute(f"SELECT * FROM {table_name} OFFSET {i} LIMIT {chunk_size};")
                rows = cursor.fetchall()
                chunk = {
                    "columns": columns,
                    "data": [dict(zip(columns.keys(), row)) for row in rows]
                }
                data_chunks.append(chunk)
        else:
            cursor.execute(f"SELECT * FROM {table_name};")
            rows = cursor.fetchall()
            data_chunks.append({
                "columns": columns,
                "data": [dict(zip(columns.keys(), row)) for row in rows]
            })

        db_data[table_name] = {
            "total_rows": row_count,
            "chunks": data_chunks
        }

    cursor.close()
    conn.close()

    return db_data

def scrape_full_page(url):
    """Belirtilen URL'deki tüm metni alır ve parçalara böler."""
    headers = {"User-Agent": "Mozilla/5.0"}
    response = requests.get(url, headers=headers)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, "html.parser")

        # Sayfadaki metni çek
        paragraphs = soup.find_all(["p", "h1", "h2", "h3", "li"])
        full_text = "\n".join([p.get_text() for p in paragraphs])

        # Eğer metin çok uzunsa, 2000 karakterlik parçalara böl
        max_chunk_size = 2000
        chunks = [full_text[i:i+max_chunk_size] for i in range(0, len(full_text), max_chunk_size)]

        return chunks
    return []

def scrape_web(query, site="google"):
    """Web scraping yaparak ilgili konularda güncel bilgi çeker."""
    if site == "google":
        search_url = f"https://www.google.com/search?q={query.replace(' ', '+')}"
    elif site == "scholar":
        search_url = f"https://scholar.google.com/scholar?q={query.replace(' ', '+')}"
    else:
        return []

    headers = {"User-Agent": "Mozilla/5.0"}
    response = requests.get(search_url, headers=headers)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, "html.parser")
        results = soup.find_all("a")

        extracted_data = []
        for result in results[:5]:  # İlk 5 sonucu al
            link = result.get("href")
            if link and link.startswith("http"):
                extracted_data.append(link)

        return extracted_data
    return []

def generate_response(prompt):
    """GPT-2 Modelini Kullanarak Cevap Üretir"""
    inputs = tokenizer(prompt, return_tensors="pt", max_length=5120, truncation=True)
    outputs = model.generate(**inputs, max_length=4096, pad_token_id=tokenizer.eos_token_id)
    response = tokenizer.decode(outputs[0], skip_special_tokens=True)
    return response.strip()

if __name__ == "__main__":
    input_query = sys.argv[1]
    database_data = fetch_database_data()

    # Google & Scholar'dan linkleri al
    google_links = scrape_web(input_query, "google")
    scholar_links = scrape_web(input_query, "scholar")

    system_prompt = "Aşağıdaki veritabanı ve güncel web verisini analiz et. Sadece ilgili konulara odaklanarak yanıt ver:\n"

    collected_responses = []

    # Veritabanını parça parça işleme
    for table_name, table_data in database_data.items():
        for index, chunk in enumerate(table_data["chunks"]):
            formatted_db_data = json.dumps(chunk, indent=2, ensure_ascii=False)

            if len(formatted_db_data) > 1000:
                formatted_db_data = formatted_db_data[:1000] + "\n...(veri büyük olduğu için kesildi)"

            full_prompt = f"{system_prompt}Tablo: {table_name} (Bölüm {index + 1})\n{formatted_db_data}"
            response = generate_response(full_prompt)

            collected_responses.append(f"Tablo: {table_name} (Bölüm {index + 1}): {response}")

    # Web sayfalarını parçalar halinde işleme
    for link in google_links + scholar_links:
        web_chunks = scrape_full_page(link)
        for idx, chunk in enumerate(web_chunks):
            web_prompt = f"Web Sayfası ({link}) - Bölüm {idx + 1}:\n{chunk}"
            response = generate_response(web_prompt)
            collected_responses.append(response)

    # Nihai model analizi
    final_prompt = "\n\n".join(collected_responses) + f"\n\nSon olarak şu soruya yanıt ver:\n{input_query}"
    final_answer = generate_response(final_prompt)

    print(json.dumps({"response": final_answer}, ensure_ascii=False))
