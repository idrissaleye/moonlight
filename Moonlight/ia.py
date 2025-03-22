from flask import Flask, request, jsonify
from flask_cors import CORS
import ollama

app = Flask(__name__)
CORS(app)  # Autoriser les requêtes depuis l'extérieur

# Initialisation Ollama
print("Starting...")
client = ollama.Client()

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.get_json()
        prompt = data.get("prompt")

        if not prompt:
            return jsonify({"error": "Missing prompt"}), 400

        # Générer une réponse à partir du modèle
        response = client.generate(model="deepseek-r1:1.5b", prompt=prompt)

        return jsonify({"response": response["response"]})
    
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
