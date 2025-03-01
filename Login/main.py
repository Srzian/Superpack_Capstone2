import json
import base64
import cv2
import datetime
import numpy as np
import mediapipe as mp
import mysql.connector
from flask import Flask, request, jsonify
from flask_cors import CORS, cross_origin
from scipy.spatial.distance import euclidean

# Initialize mediapipe face mesh
mp_drawing = mp.solutions.drawing_utils
mp_drawing_styles = mp.solutions.drawing_styles
mp_face_mesh = mp.solutions.face_mesh

context = (r'C:\xampp\apache\conf\ssl.crt\server.crt', r'C:\xampp\apache\conf\ssl.key\server.key')


face_mesh = mp_face_mesh.FaceMesh(
    static_image_mode=True, 
    max_num_faces=1, 
    min_detection_confidence=0.5
)

# Define the connection
connection = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="face_id",
    port = "3306"
)

# Define the cursor
cursor = connection.cursor()

app = Flask(__name__)

CORS(app, resources={r"/*": {"origins": "https://superpack-adu.com"}})

@app.route('/Face_API/receive', methods=['POST'])
@cross_origin()  # This decorator is optional if you've set CORS globally
def receive_image():
    try:
        # Parse the incoming JSON data
        data = request.get_json()
        base64_string = data.get('image')
        
        # Check if the image data is present
        if not base64_string:
            return jsonify({"error": "No image data provided"}), 400

        # Decode the base64 string to get the image bytes
        image_data = base64.b64decode(base64_string)
        
        # Process the image data as needed
        # For demonstration, we'll just print the first 100 bytes
        print(image_data[:100])

        # Respond back to the client
        return jsonify({"message": "Image received and processed"}), 200
    
    except Exception as e:
        return jsonify({"error": str(e)}), 400



@app.route('/Face_API/register', methods=['POST'])
@cross_origin()  # This decorator is optional if you've set CORS globally
def register_user():
    try:
        # Parse the incoming JSON data
        data = request.get_json()
        base64_string = data.get('image')
        name = data.get('name')
        role = data.get('role')
        department = data.get('department')  # Fixed spelling

        if not name or not role or not department:
            return jsonify({"success": False, "message": "Name, role, and department are required"}), 400
        
        # Check if the user is already registered
        query = "SELECT * FROM register WHERE name = %s"
        cursor.execute(query, (name,))
        result = cursor.fetchall()

        if result:
            return jsonify({"success": False, "message": "User already registered"}), 400
        
        # Ensure the image is provided
        if not base64_string:
            return jsonify({"success": False, "message": "Image data is required"}), 400
        
        # Decode the base64 string to get the image bytes
        image_data = base64.b64decode(base64_string)

        # Convert bytes data to a NumPy array
        nparr = np.frombuffer(image_data, np.uint8)

        # Decode the image from the NumPy array (OpenCV expects this format)
        image = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

        # Process the image and detect face mesh
        results = face_mesh.process(image_rgb)

        if results.multi_face_landmarks:
            # Get the landmark coordinates and convert them to a list
            face_landmarks = results.multi_face_landmarks[0]
            landmarks = [[landmark.x, landmark.y, landmark.z] for landmark in face_landmarks.landmark]
            
            # Convert the landmarks list to a JSON string
            landmarks_json = json.dumps(landmarks)

            # Insert the user's data and landmarks into the database
            query = "INSERT INTO register (name, role, department, landmarks_hash) VALUES (%s, %s, %s, %s)"
            cursor.execute(query, (name, role, department, landmarks_json))
            connection.commit()

            return jsonify({"success": True, "message": f"{name} Registered Successfully"}), 200
        else:
            return jsonify({"success": False, "message": "No face detected, please try again"}), 400

    except Exception as e:
        return jsonify({"error": str(e)}), 400


@app.route('/Face_API/mark-attendance', methods=['POST'])
@cross_origin()  # This decorator is optional if you've set CORS globally
def mark_attendance():
    try:
        # Parse the incoming JSON data
        data = request.get_json()
        base64_string = data.get('image')
        name = data.get('name')

        if not base64_string:
            return jsonify({"success": False, "message": "No image data provided"}), 400

        # Decode the base64 string to get the image bytes
        image_data = base64.b64decode(base64_string)

        # Convert bytes data to a NumPy array
        nparr = np.frombuffer(image_data, np.uint8)

        # Decode the image from the NumPy array (OpenCV expects this format)
        image = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

        # Process the image and detect face mesh
        results = face_mesh.process(image_rgb)

        if results.multi_face_landmarks:
            # Get the landmark coordinates of the current face
            face_landmarks = results.multi_face_landmarks[0]
            current_landmarks = [[landmark.x, landmark.y, landmark.z] for landmark in face_landmarks.landmark]

            # Retrieve the stored landmarks from the database
            query = "SELECT landmarks_hash, department, role FROM register WHERE name = %s"
            cursor.execute(query, (name,))
            result = cursor.fetchone()

            if result:
                stored_landmarks = json.loads(result[0])  # Deserialize the stored landmarks
                department = result[1]
                role = result[2]

                # Calculate the Euclidean distance between current and stored landmarks
                distances = [euclidean(current_landmarks[i], stored_landmarks[i]) for i in range(len(current_landmarks))]
                average_distance = np.mean(distances)

                # Set a similarity threshold
                threshold = 0.1  # Adjust this based on accuracy needs

                if average_distance < threshold:
                    # Check if the user has already marked attendance
                    query = "SELECT time_in FROM attendance WHERE name = %s"
                    cursor.execute(query, (name,))
                    res = cursor.fetchone()

                    if res and res[0] is not None:
                        response = jsonify({"success": True, "message": "Attendance already marked", "name": name, "department": department, "role": role})
                    else:
                        # Insert attendance if it's not already marked
                        query = "INSERT INTO attendance (name, role, time_in) VALUES (%s, %s, %s)"
                        cursor.execute(query, (name, role, datetime.datetime.now()))
                        connection.commit()

                        response = jsonify({"success": True, "message": "Attendance marked successfully", "name": name, "department": department, "role": role})
                else:
                    response = jsonify({"success": False, "message": "Face not recognized, try again", "status": "Registration status: Failed"})
            else:
                response = jsonify({"success": False, "message": "User not registered"})
        else:
            response = jsonify({"success": False, "message": "No face detected, please try again"})

        return response, 200

    except Exception as e:
        return jsonify({"error": str(e)}), 400



if __name__ == '__main__':
    app.run(
        debug=True, 
        host='0.0.0.0', 
        port=5000, 
        ssl_context=context
    )