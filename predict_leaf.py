import sys
import os
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'  # Suppress INFO/WARNING
import joblib
import numpy as np
import cv2
import tensorflow as tf
from tensorflow.keras.models import load_model

# Suppress TensorFlow runtime warnings
tf.get_logger().setLevel('ERROR')

# Mapping class indices to disease names
classes = {
    0: "Anthracnose",
    1: "Bacterial Canker",
    2: "Cutting Weevil",
    3: "Die Back",
    4: "Gall Midge",
    5: "Powdery Mildew",
    6: "Sooty Mould",
    7: "Healthy"
}

# Disease treatment suggestions
disease_suggestions = {
    "Anthracnose": "Remove infected leaves, apply copper-based fungicides, and avoid overhead watering.",
    "Bacterial Canker": "Prune during dry weather, sterilize tools, apply Bordeaux mixture or copper sprays.",
    "Cutting Weevil": "Use pheromone traps and biological control agents like Beauveria bassiana.",
    "Die Back": "Ensure proper drainage, avoid waterlogging, and use fungicides such as Mancozeb.",
    "Gall Midge": "Spray neem oil or Imidacloprid, and destroy infected leaves early.",
    "Powdery Mildew": "Ensure good air circulation, avoid late watering, and use sulfur-based fungicides.",
    "Sooty Mould": "Control underlying pest issues (aphids, whiteflies), and clean leaves with soap solution.",
    "Healthy": "Your plant is healthy. Maintain good watering, pruning, and disease monitoring habits."
}

IMG_SIZE = (128, 128)

# Base directory of the script
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Load models safely
try:
    model_path = os.path.join(BASE_DIR, "model/mango_disease_model.pkl")
    feature_extractor_path = os.path.join(BASE_DIR, "model/mobilenet_feature_extractor.h5")
    
    model = joblib.load(model_path)
    feature_extractor = load_model(feature_extractor_path, compile=False)
except Exception as e:
    print(f"Prediction failed: Failed to load model or feature extractor: {e}")
    sys.exit(1)

def predict_image(image_path):
    if not os.path.exists(image_path):
        raise FileNotFoundError(f"Image not found: {image_path}")

    # Read and preprocess the image
    img = cv2.imread(image_path)
    if img is None:
        raise ValueError(f"Failed to read image: {image_path}")

    img = cv2.resize(img, IMG_SIZE)
    img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
    img = img / 255.0
    img = np.expand_dims(img, axis=0)

    # Extract features and predict
    features = feature_extractor.predict(img, verbose=0)
    pred_probs = model.predict(features)

    # Handle different output shapes
    if isinstance(pred_probs, np.ndarray):
        if pred_probs.ndim == 2 and pred_probs.shape[1] > 1:
            pred_index = np.argmax(pred_probs[0])
        else:
            pred_index = int(pred_probs[0])
    else:
        pred_index = int(pred_probs)

    disease_name = classes.get(pred_index, "Unknown")
    suggestion = disease_suggestions.get(disease_name, "No suggestion available.")
    
    return disease_name, suggestion

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Prediction failed: No image path provided.")
        sys.exit(1)

    image_path = sys.argv[1]

    try:
        disease, suggestion = predict_image(image_path)
        # Print **only** the disease and suggestion (PHP will parse this)
        print(f"{disease}::{suggestion}")
    except Exception as e:
        print(f"Prediction failed: {e}")
