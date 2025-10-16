from flask import Flask, request, jsonify

app = Flask(__name__)

# --- Dataset kata positif & negatif ---
positive_words = [
    'bagus', 'mantap', 'suka', 'puas', 'baik', 'luar biasa', 'menyenangkan', 'hebat', 'terbaik', 'memuaskan',
    'recommended', 'top', 'bagus sekali', 'keren', 'wow', 'ciamik', 'istimewa', 'menakjubkan', 'super', 
    'bagus banget', 'berkualitas', 'terpercaya', 'ramah', 'cepat', 'nyaman', 'enak', 'memuaskan banget', 
    'profesional', 'mempermudah', 'worth it', 'tepat waktu', 'mudah digunakan', 'kualitas oke', 'hasil memuaskan',
    'bermanfaat', 'mantap banget', 'bintang lima', 'rekomendasi', 'the best', 'bagus deh', 'pelayanan baik', 
    'puas banget', 'sesuai harapan', 'efisien', 'aman', 'praktis', 'terjangkau', 'sangat membantu', 'bagus terus',
    'juara', 'berhasil', 'inspiratif', 'sukses', 'nyenengin', 'menenangkan', 'nyatu banget', 'bagus parah'
]

negative_words = [
    'buruk', 'kecewa', 'jelek', 'menyedihkan', 'parah', 'tidak puas', 'mengecewakan', 'salah', 'payah', 
    'tidak recommended', 'menyesal', 'tidak suka', 'lambat', 'jelek banget', 'kurang baik', 'kurang memuaskan',
    'nggak enak', 'nggak sesuai', 'parah banget', 'error', 'nge-lag', 'gagal', 'menyebalkan', 'tidak ramah',
    'tidak sopan', 'tidak bagus', 'tidak layak', 'tidak membantu', 'ribet', 'malas banget', 'menyulitkan',
    'tidak profesional', 'palsu', 'mahal banget', 'tidak sesuai harapan', 'menyita waktu', 'berantakan',
    'menakutkan', 'buruk banget', 'ngecewain', 'sampah', 'kurang cepat', 'jelek parah', 'bikin emosi', 
    'nggak jelas', 'malesin', 'nge-bug', 'tidak stabil', 'nggak berguna', 'zonk', 'menyesalkan', 'kurang responsif'
]

# --- Fungsi klasifikasi sentimen ---
def classify_review(review):
    words = review.lower().split()

    positive_count = sum(word in positive_words for word in words)
    negative_count = sum(word in negative_words for word in words)

    if positive_count > negative_count:
        return "Positif"
    elif positive_count < negative_count:
        return "Negatif"
    else:
        return "Netral"


# --- Endpoint API ---
@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()

    if not data or 'review' not in data:
        return jsonify({'error': 'Masukkan field "review" di body JSON'}), 400

    review = data['review']
    sentiment = classify_review(review)

    return jsonify({
        'review': review,
        'sentiment': sentiment
    })


if __name__ == '__main__':
    app.run(debug=True)
