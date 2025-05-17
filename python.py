from flask import Flask, Response
import cv2
import time
import os
from ultralytics import YOLO

# Initialize Flask app
app = Flask(__name__)
model = YOLO("yolov8n.pt")  # Load your YOLO model (use the correct model file)

# Path to save detected images (adjust the path to your Laravel public directory)
SAVE_DIR = r"C:/Users/Edrian/Documents/4th Year/GuardHive/GuardHive/public/detected_images"  # Replace this path
os.makedirs(SAVE_DIR, exist_ok=True)  # Ensure directory exists

# ESP32-CAM stream URL (replace with your ESP32-CAM's actual IP and stream port)
stream_url = "http://192.168.5.115:81/stream"

def generate():
    cap = cv2.VideoCapture(stream_url)
    while True:
        ret, frame = cap.read()
        if not ret:
            continue

        # Run YOLO detection on the frame
        results = model(frame)

        # Loop through all detections
        for r in results:
            boxes = r.boxes
            for box in boxes:
                if int(box.cls) == 0:  # Class 0 represents 'person' in COCO dataset
                    # Draw a bounding box around the detected person
                    xyxy = box.xyxy[0].cpu().numpy().astype(int)
                    cv2.rectangle(frame, (xyxy[0], xyxy[1]), (xyxy[2], xyxy[3]), (0, 255, 0), 2)

                    # Capture the image when a person is detected
                    timestamp = time.strftime("%Y%m%d_%H%M%S")
                    filename = os.path.join(SAVE_DIR, f"detected_{timestamp}.jpg")
                    cv2.imwrite(filename, frame)  # Save the captured frame to the file
                    print(f"[{timestamp}] Person detected! Saved: {filename}")

        # Encode frame as JPEG and yield to Flask
        ret, jpeg = cv2.imencode('.jpg', frame)
        if not ret:
            continue

        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + jpeg.tobytes() + b'\r\n')

@app.route('/stream')
def stream():
    return Response(generate(), mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
