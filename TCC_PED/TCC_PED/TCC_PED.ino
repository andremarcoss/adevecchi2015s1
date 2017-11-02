/*
 * Projeto:
 *  - Ponto Eletrônico Digital
 *
 * Descrição:
 *  - TCC para a Faccamp Facundade Campo Limpo Paulista,
 *    como requesito parcial para obtenção do título de
 *    Bacharel em Ciência da Computação.
 *
 *  - Este projeto é um Ponto Eletrônico Digital com interface
 *    ethernet para persistir os registros de data e hora a uma
 *    base de dados em um servidor Web
 *
 * Circuito:
 *  - Ethernet shield utiliza os pinos 10, 11, 12, 13
 *
 *  - RFID-RC522 breakout utiliza os pinos 8, 9, 11, 12, 13
 *
 *  - RTC DS1307 breakout utiliza os pinos AN4 e AN5
 *
 *  - LCD 16x2 utiliza os pinos 2, 3, 4, 5, 6, 7
 *
 * criado em 26 Abril 2015
 * by Andre Devecchi
 * contato: andre.devecchi@gmail.com
 * ----------------------------------------------------------------------------
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Devecchi, André; Silva, André; faccuser@gmail.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

#include <SPI.h>
#include <DS1307.h>
#include <MFRC522.h>
#include <Ethernet.h>
#include <ArduinoJson.h>
#include <LiquidCrystal.h>

char* requestServer();
const char* parseJson();

byte status;
char* jsonString;

// Inicializa RTC
DS1307 rtc(A4, A5);

// Inicializa MFRC522
#define SS_PIN 9
#define RST_PIN 8
MFRC522 mfrc522(SS_PIN, RST_PIN);

// Endereço MAC do Ethernet Shield
byte mac[] = { 
  0x90, 0xA2, 0xDA, 0x0F, 0x3A, 0xDC };

// Endereço IP do Servidor
IPAddress  server( 192, 168, 1, 100 ); //server( 192, 168, 1, 105 );

// Endereço IP do Arduino
IPAddress ip( 192, 168, 1, 110 );

// Inicializa o Ethernet client library
// com o endereço IP do servidor (porta 80 é padrão para HTTP)
EthernetClient client;

// Inicializa LCD
LiquidCrystal lcd(7, 6, 5, 4, 3, 2);

void setup() {
  SPI.begin();          // Inicia  SPI bus
  mfrc522.PCD_Init();   // Inicia MFRC522
  
  rtc.halt(false);      //Aciona o relogio
   
  lcd.begin(16, 2);    //Define o LCD com 16 colunas e 2 linhas
  
  //As linhas abaixo setam a data e hora do modulo
  //e podem ser comentada apos a primeira utilizacao
 
  //rtc.setDOW(THURSDAY);        //Define o dia da semana
  //rtc.setTime(18, 22, 00);   //Define o horario
  //rtc.setDate(14, 5, 2015);  //Define o dia, mes e ano
   
  //Definicoes do pino SQW/Out
  rtc.setSQWRate(SQW_RATE_1);
  rtc.enableSQW(true);
  
  Serial.begin(9600);

  // inicia a conexão Ethernet
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Falha para configurar Ethernet usando DHCP");

    Ethernet.begin(mac, ip);
  }
  // da ao Ethernet shield um segundo para inicializar
  delay(1000);
  Serial.println("Conectando...");
}

void loop()
{
  // Look for new cards
  if ( ! mfrc522.PICC_IsNewCardPresent()) 
  {
    //return;
    status = 0;
  }
  // Select one of the cards
  if ( ! mfrc522.PICC_ReadCardSerial()) 
  {
    //return;
    status = 0;
  }
  else 
  {
     status = 1; 
  }
  
  switch (status)
  {
    case 0:
      //Mostra a hora atual no display
      lcd.setCursor(4, 0);
      lcd.print(rtc.getTimeStr());
       
      //Mostra a data atual no display
      lcd.setCursor(1, 1);
      lcd.print(rtc.getDateStr(FORMAT_LONG, FORMAT_LITTLEENDIAN, '/'));
      
      //Mostra o dia da semana no display
      lcd.setCursor(12, 1);
      lcd.print(rtc.getDOWStr(FORMAT_SHORT));
       
      //Aguarda 1 segundo e repete o processo
      delay (1000);
      break;
    case 1:
      String conteudo= "";
      for (byte i = 0; i < mfrc522.uid.size; i++) 
      {
         conteudo.concat(String(mfrc522.uid.uidByte[i] < 0x10 ? " 0" : " "));
         conteudo.concat(String(mfrc522.uid.uidByte[i], HEX));
      }
      conteudo.toUpperCase();
      conteudo.trim();
      
      jsonString = requestServer(conteudo);
      
      const char* nome = parseJson();
      Serial.println();
      Serial.println(nome);
      
      lcd.clear();
      lcd.setCursor(1,0);
      lcd.print(nome); 
      
      Time t = rtc.getTime();
      if (t.hour < 12)
      {
        lcd.setCursor(4,1);
        lcd.print("Bom Dia!");  
      }
      else if (t.hour < 18)
      {
        lcd.setCursor(3,1);
        lcd.print("Boa Tarde!");           
      }
      else 
      {
        lcd.setCursor(3,1);
        lcd.print("Boa Noite!");
      }
      delay(3000);
      lcd.clear();
      break;
  }
}

char* requestServer(String uid)
{
  boolean readJson = false;
  byte i = 0;
  char* output = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
  String data;
  
  data = "uid="+uid+"&hora="+rtc.getTimeStr()+"&data="+rtc.getDateStr(FORMAT_LONG, FORMAT_LITTLEENDIAN, '/')+"&dia="+rtc.getDOWStr(FORMAT_SHORT);
  
  // inicia conexão com servidor
  if (client.connect(server, 80)) {
    Serial.println("Conectado");

    client.println("POST /tcc/ponto-eletronico.php HTTP/1.1");
    client.println("Host: 192.168.1.100");
    client.println("User-Agent: Arduino/1.0");
    client.println("Connection: close");
    client.println("Content-Type: application/x-www-form-urlencoded;");
    client.print("Content-Length: ");
    client.println(data.length());
    client.println();
    client.println(data);
    client.println();
    
    //Aguardando conexao
    while(!client.available()){
      delay(1);
    }
  
    // Percorre os caracteres do envelope HTTP do servidor e armazena na String apenas o conteudo JSON
    while ( client.available() ) {
      char c = client.read();

      if ( c == '{' ) {
        readJson = true;
      }
      if ( readJson ) {
        output[i++] = c; 
        if ( c == '}' ) {
          output[i] = '0';
        }
      }
    }
  }
  else {
    // não hove conexão com o servidor
    Serial.println("Conexão Falhou");
  }
  
  //Aguarda a desconexao com o servidor
  while( client.connected() ) {
    delay(1);
  }
  
   // desconectado o cliente
  if (!client.connected()) {
    Serial.println();
    Serial.println("Desconectado.");
    client.stop();
  }
  
  return output;
}

const char* parseJson()
{
  StaticJsonBuffer<29> jsonBuffer;

  JsonObject& root = jsonBuffer.parseObject(jsonString);

  if (!root.success()) {
    return "parseObject() failed";
  }
  
  return root["nome"];
}





