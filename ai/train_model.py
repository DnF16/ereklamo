import pandas as pd
import joblib

from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.linear_model import LogisticRegression
from sklearn.pipeline import Pipeline
from sklearn.metrics import accuracy_score, classification_report


# Load dataset
df = pd.read_csv("ai/dataset/complaints.csv")

print("Dataset loaded.")
print(f"Total complaints: {len(df)}")

# Check required columns
if "description" not in df.columns or "priority" not in df.columns:
    raise ValueError(
        "CSV must contain 'description' and 'priority' columns."
    )

# Remove empty rows
df = df.dropna(subset=["description", "priority"])

X = df["description"]
y = df["priority"]

# Split dataset
X_train, X_test, y_train, y_test = train_test_split(
    X,
    y,
    test_size=0.2,
    random_state=42,
    stratify=y
)

# Create NLP + ML pipeline
model = Pipeline([
    (
        "tfidf",
        TfidfVectorizer(
            lowercase=True,
            stop_words="english",
            ngram_range=(1, 2)
        )
    ),
    (
        "classifier",
        LogisticRegression(
            max_iter=1000
        )
    )
])

print("\nTraining priority model...")

model.fit(X_train, y_train)

print("Training complete.")

# Evaluate model
y_pred = model.predict(X_test)

accuracy = accuracy_score(y_test, y_pred)

print(f"\nAccuracy: {accuracy:.2%}")

print("\nClassification Report:")
print(classification_report(y_test, y_pred, zero_division=0))

# Save model
model_path = "ai/model/priority_model.pkl"

joblib.dump(model, model_path)

print(f"\nPriority model saved to: {model_path}")