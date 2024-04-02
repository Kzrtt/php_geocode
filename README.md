# Documentação da API de Geolocalização

Esta API fornece serviços de geolocalização, permitindo que usuários obtenham endereços a partir de coordenadas geográficas (latitude e longitude) e vice-versa. Utiliza a API do Google Maps Geocoding para realizar as operações. Abaixo estão detalhados os métodos disponíveis e como utilizá-los.

## Métodos Disponíveis

### 1. Obter Endereço (`getAddress`)

Converte coordenadas geográficas (latitude e longitude) em um endereço postal.

#### Requisição: 
- **Tipo:** `POST`

- **Corpo da requisição (JSON):**
    ```json
    {
        "action": "getAddress",
        "lat": "<latitude>",
        "lng": "<longitude>"
    }
    ```
- **Parâmetros:**
    - `lat`: Latitude da localização.
    - `lng`: Longitude da localização.

#### Resposta:
- **Tipo:** `application/json`

- **Corpo da resposta (JSON):**
    ```json
    {
        "status": true|false,
        "data": {
            "address": "<endereço formatado>"
        },
        "message": "Endereço retornado com sucesso"|"Erro ao retornar endereço"
    }
    ```

### 2. Obter Coordenadas (`getCoordinates`)

Converte um endereço postal em coordenadas geográficas (latitude e longitude).

#### Requisição:
- **Tipo:** `POST`

- **Corpo da requisição (JSON):**
    ```json
    {
        "action": "getCoordinates",
        "street": "<rua>",
        "number": "<número>",
        "city": "<cidade>",
        "state": "<sigla do estado>",
        "postalCode": "<CEP>"
    }
    ```
- **Parâmetros:**
    - `street` (opcional): Nome da rua.
    - `number` (opcional): Número do endereço na rua.
    - `city` (opcional): Cidade do endereço.
    - `state` (opcional): Sigla do estado. Use as siglas brasileiras, como "SP" para São Paulo.
    - `postalCode` (opcional): Código Postal (CEP) do endereço.

#### Resposta:
- **Tipo:** `application/json`

- **Corpo da resposta (JSON):**
    ```json
    {
        "status": true|false,
        "data": {
            "lat": <latitude>,
            "lng": <longitude>
        },
        "message": "Coordenadas retornadas com sucesso"|"Erro ao retornar coordenadas"
    }
    ```
