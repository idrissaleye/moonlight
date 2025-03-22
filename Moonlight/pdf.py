import pymysql
import os
import fitz  # PyMuPDF pour traiter les PDFs
import ollama
import time

# Configuration de la connexion à MySQL
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "plateforme_examens",
}

# Fonction pour exécuter une requête SQL
def execute_query(query, params=None, fetch=False):
    conn = pymysql.connect(**DB_CONFIG)
    cursor = conn.cursor()
    cursor.execute(query, params or ())
    result = cursor.fetchall() if fetch else None
    conn.commit()
    cursor.close()
    conn.close()
    return result

# Fonction pour extraire le texte d'un PDF
def extract_text_from_pdf(pdf_path):
    doc = fitz.open(pdf_path)
    text = "\n".join([page.get_text() for page in doc])
    return text

# Fonction pour générer un corrigé avec l'IA
def generate_corrige(exam_text):
    prompt = f"""
    Tu es un professeur expert. Voici une épreuve d'examen :

    {exam_text}

    Génère un corrigé détaillé avec des réponses justifiées pour chaque question.
    """
    response = ollama.chat(model="deepseek-r1:1.5b", messages=[{"role": "user", "content": prompt}])
    return response["message"]["content"]

# Fonction pour créer un PDF
def save_text_to_pdf(text, output_path):
    doc = fitz.open()
    page = doc.new_page()
    page.insert_text((50, 50), text)
    doc.save(output_path)

# Vérifier et traiter les nouveaux sujets d'examen
def process_new_exams():
    print("🔍 Vérification des nouveaux sujets...")
    exams = execute_query("SELECT id, fichier_chemin FROM sujets_examen WHERE id NOT IN (SELECT sujet_examen_id FROM corriges)", fetch=True)

    for exam_id, file_path in exams:
        if not os.path.exists(file_path):
            print(f"❌ Fichier non trouvé: {file_path}")
            continue

        print(f"📄 Analyse du fichier: {file_path}")
        exam_text = extract_text_from_pdf(file_path)
        corrige_text = generate_corrige(exam_text)

        # Sauvegarde du corrigé dans un PDF
        corrige_path = file_path.replace(".pdf", "_corrige.pdf")
        save_text_to_pdf(corrige_text, corrige_path)

        # Insertion dans la base de données
        execute_query("INSERT INTO corriges (sujet_examen_id, fichier_chemin) VALUES (%s, %s)", (exam_id, corrige_path))
        print(f"✅ Corrigé généré et stocké: {corrige_path}")

# Comparer une copie avec son corrigé et attribuer une note
def evaluate_copy(student_text, corrige_text):
    prompt = f"""
    Voici une copie d'étudiant :

    {student_text}

    Voici le corrigé officiel :

    {corrige_text}

    Compare la copie avec le corrigé et attribue une note sur 20 en expliquant les erreurs et les points positifs.
    Réponds sous ce format : "Note: X/20 - Commentaire: ..."
    """
    response = ollama.chat(model="deepseek", messages=[{"role": "user", "content": prompt}])
    return response["message"]["content"]

# Vérifier et traiter les nouvelles copies des étudiants
def process_student_copies():
    print("🔍 Vérification des nouvelles copies...")
    copies = execute_query("SELECT id, etudiant_id, sujet_examen_id, fichier_chemin FROM copies_etudiants WHERE id NOT IN (SELECT copie_etudiant_id FROM notes)", fetch=True)

    for copy_id, student_id, exam_id, file_path in copies:
        corrige = execute_query("SELECT fichier_chemin FROM corriges WHERE sujet_examen_id = %s", (exam_id,), fetch=True)

        if not corrige:
            print(f"⚠️ Pas de corrigé trouvé pour le sujet {exam_id}.")
            continue

        corrige_path = corrige[0][0]
        if not os.path.exists(file_path) or not os.path.exists(corrige_path):
            print(f"❌ Fichier manquant: {file_path} ou {corrige_path}")
            continue

        print(f"📄 Évaluation de la copie: {file_path}")
        student_text = extract_text_from_pdf(file_path)
        corrige_text = extract_text_from_pdf(corrige_path)

        evaluation = evaluate_copy(student_text, corrige_text)

        # Extraire la note du retour de l'IA
        try:
            note = int(evaluation.split("Note: ")[1].split("/20")[0].strip())
            commentaire = evaluation.split("Commentaire: ")[1].strip()
        except:
            note = 0
            commentaire = "Erreur d'analyse."

        # Stocker la note dans la base de données
        execute_query("INSERT INTO notes (copie_etudiant_id, note, commentaire, enseignant_id, date_attribution) VALUES (%s, %s, %s, %s, NOW())",
                      (copy_id, note, commentaire, None))
        print(f"✅ Note attribuée: {note}/20")

# Boucle principale de surveillance
def main():
    print("🚀 L'IA surveille la base de données...")
    while True:
        process_new_exams()
        process_student_copies()
        print("🔄 Attente avant la prochaine vérification...")
        time.sleep(30)  # Vérifie toutes les 30 secondes

if __name__ == "__main__":
    main()
