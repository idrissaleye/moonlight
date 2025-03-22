import pymysql
import fitz  # PyMuPDF for PDF processing
import ollama
from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
import io

# MySQL Connection Settings
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "plateforme-examens",
}

# Function to retrieve a PDF from MySQL
def fetch_pdf_from_db():
    conn = pymysql.connect(**DB_CONFIG)
    cursor = conn.cursor()
    
    cursor.execute("SELECT id, file_name, file_data, classe FROM sujet_examen LIMIT 1;")
    result = cursor.fetchone()
    
    if result:
        pdf_id, file_name, file_data, classe = result
        with open("retrieved_questions.pdf", "wb") as file:
            file.write(file_data)
        cursor.close()
        conn.close()
        return pdf_id, file_name, "retrieved_questions.pdf", classe
    else:
        cursor.close()
        conn.close()
        print("No PDF found in sujet_examen.")
        return None, None, None, None

# Function to extract questions from the PDF
def extract_questions_from_pdf(pdf_path):
    doc = fitz.open(pdf_path)
    text = "\n".join([page.get_text() for page in doc])
    return text.strip()

# Function to generate answers using LLM (DeepSeek-R1 via Ollama)
def generate_answers(questions_text):
    prompt = f"""
    You are an AI assistant. The following text contains 10 exam questions. Please generate clear and accurate answers for each question.

    Questions:
    {questions_text}

    Provide answers in a structured format.
    """
    response = ollama.chat(model="deepseek-r1:1.5b", messages=[{"role": "user", "content": prompt}])
    return response["message"]["content"]

# Function to create a new PDF with both questions and answers
def create_answered_pdf(questions_text, answers_text, output_pdf_path):
    buffer = io.BytesIO()
    c = canvas.Canvas(buffer, pagesize=letter)
    c.setFont("Helvetica", 12)

    content = f"Exam Questions:\n\n{questions_text}\n\nAnswers:\n\n{answers_text}"
    lines = content.split("\n")
    
    y_position = 750
    for line in lines:
        if y_position < 50:
            c.showPage()
            c.setFont("Helvetica", 12)
            y_position = 750
        c.drawString(50, y_position, line)
        y_position -= 20
    
    c.save()
    
    with open(output_pdf_path, "wb") as f:
        f.write(buffer.getvalue())

# Function to upload the generated PDF to MySQL
def upload_pdf_to_db(file_name, file_path, classe):
    conn = pymysql.connect(**DB_CONFIG)
    cursor = conn.cursor()

    with open(file_path, "rb") as file:
        file_data = file.read()

    cursor.execute(
        "INSERT INTO corrige_type (file_name, file_data, classe) VALUES (%s, %s, %s)",
        (file_name, file_data, classe),
    )
    conn.commit()
    cursor.close()
    conn.close()
    print(f"Uploaded {file_name} to corrige_type.")

# Main Execution
pdf_id, file_name, retrieved_pdf, classe = fetch_pdf_from_db()
if retrieved_pdf:
    questions = extract_questions_from_pdf(retrieved_pdf)
    answers = generate_answers(questions)
    
    output_pdf = "answered_exam.pdf"
    create_answered_pdf(questions, answers, output_pdf)

    upload_pdf_to_db(f"corrige_{file_name}", output_pdf, classe)
