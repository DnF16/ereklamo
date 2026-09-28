from fastapi import FastAPI
from pydantic import BaseModel
import joblib


# Load trained ML model
model = joblib.load("ai/model/priority_model.pkl")

app = FastAPI()


# Data received from Laravel
class Complaint(BaseModel):
    description: str


@app.get("/")
def home():
    return {
        "message": "eREKLAMO AI API is running"
    }


@app.post("/predict")
def predict_priority(complaint: Complaint):

    # Use the same input format used during model training
    text = complaint.description

    # Predict priority
    prediction = model.predict([text])[0]

    return {
        "description": complaint.description,
        "priority": prediction
    }