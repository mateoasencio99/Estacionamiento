#include <WiFi.h>
#include <WiFiClient.h>
#include <WiFiServer.h>
#include <WiFiUdp.h>

int sensor_Pin = 0, LED_Pin = 2;  // Pin 2 de Arduino conectado a la salida del sensor
int D = 0, O = 0, ID = 6;
bool guardarestado, estadoanterior = false;

#define WIFI_SSID "sele"  // Nombre de la red a conectarse
#define WIFI_PASSWORD "aqaf1682"           // Contraseña de la red
//#define WIFI_SSID "Personal-E78"
//#define WIFI_PASSWORD "COE2953E78"
void conectarWiFi() {
  delay(1000);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Conectando a Wi-Fi");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.println("Conectando...");
    delay(300);
  }
  Serial.println();
  Serial.println("Conexion exitosa");
  Serial.println(WiFi.localIP());
  Serial.println();
}

void setup() {
  Serial.begin(115200);
  pinMode(sensor_Pin, INPUT);
  pinMode(LED_Pin, OUTPUT);
  conectarWiFi();
}

void loop() {
  while (D < 5 && O < 5) {
    if (digitalRead(sensor_Pin) == 1) {
      O = 0;
      D++;
      digitalWrite(LED_Pin,LOW);
    } else {
      D = 0;
      O++;
      digitalWrite(LED_Pin,HIGH);
    }
    delay(500);
  }
  if (D >= 5) {
    guardarestado = false;
    D = 0;
  } else {
    guardarestado = true;
    O = 0;
  }
  Serial.print("Estado:");
  Serial.println(guardarestado);

  if (estadoanterior != guardarestado) {
    estadoanterior = guardarestado;
    enviarEstado(guardarestado);  //Envia el estado a django para modificarlo
  }
}

void enviarEstado(bool estado) {
  WiFiClient client;

  String url = "/actualizar_estado/";

  // Construye la cadena de datos para el cuerpo del POST
  String postData = "id=" + String(ID) + "&estado=" + String(estado ? "1" : "0");

  if (client.connect("172.29.82.119", 8000)) {
    // Envía la solicitud HTTP POST
    client.println("POST " + url + " HTTP/1.1");
    client.println("Host: 172.29.82.119");
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.println("Content-Length: " + String(postData.length()));
    client.println();
    client.println(postData);

    while (client.connected() && !client.available()) delay(1);

    while (client.connected() || client.available()) {
      char c = client.read();
      Serial.write(c);
    }
    client.stop();
  } else {
    Serial.println("Error en la conexión");
  }
}

