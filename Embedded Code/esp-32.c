

#include <WiFi.h>
#include <HTTPClient.h>
#include <WebSocketsClient.h>

const char* ssid = "Mashas";
const char* password = "12345678";

// Replace with your WebSocket server URL
const char* webSocketServerURL = "ws://192.168.137.1:8080/"; 

// Replace with the scan_card.php URL
const char* serverURL = "http://192.168.137.1/project/campus-food-management/scan_card.php";  

#define RX_PIN 16  // ESP32 RX connected to Mega TX1
#define TX_PIN 17  // ESP32 TX connected to Mega RX1

WebSocketsClient webSocket;

void setup() {
  Serial.begin(115200);
  Serial2.begin(9600, SERIAL_8N1, RX_PIN, TX_PIN);

  WiFi.begin(ssid, password);
  
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnected to WiFi");

  // Connect to WebSocket server
  webSocket.begin("192.168.240.164", 8080, "/"); 
  webSocket.onEvent(webSocketEvent);
}

void loop() {
  webSocket.loop();

  if (Serial2.available()) {
    String dataFromMega = Serial2.readStringUntil('\n');
    Serial.print("Received from Mega: ");
    Serial.println(dataFromMega);

    // Extract the UID
    // Extract the UID
    int uidIndex = dataFromMega.indexOf("UID: ") + 5;
    int nameIndex = dataFromMega.indexOf(", ", uidIndex) + 2; // Start searching for ", " after the UID
    String uid = dataFromMega.substring(uidIndex, nameIndex - 2);

    Serial.print("Extracted UID: ");
    Serial.println(uid);

    // Send the UID to the scan_card.php endpoint
    sendUidToServer(uid);
  }
}

void sendUidToServer(String uid) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverURL);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String httpRequestData = "uid=" + uid;
    int httpResponseCode = http.POST(httpRequestData);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.print("Error on sending POST: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  }
}

void webSocketEvent(WStype_t type, uint8_t * payload, size_t length) {
  switch(type) {
    case WStype_DISCONNECTED:
      Serial.printf("[WSc] Disconnected!\n");
      break;
    case WStype_CONNECTED:
      Serial.printf("[WSc] Connected to url: %s\n", payload);
      break;
    case WStype_TEXT:
      Serial.printf("[WSc] get text: %s\n", payload);
      // You can add logic here to handle messages from the server
      // For example, display messages on an LCD connected to the Mega
      break;
    case WStype_BIN:
      Serial.printf("[WSc] get binary length: %u\n", length);
      break;
    default:
      break;
  }
}