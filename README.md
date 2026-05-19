# Cadastro de Pessoas — PHP + Docker

Aplicação web em PHP com banco de dados MySQL, orquestrada com Docker Compose.
Segue a arquitetura do diagrama: **db (MySQL) → app (PHP+Apache) → web (Nginx)**.

## Estrutura

```
projeto_php/
├── docker-compose.yml
├── db/
│   └── init.sql              # Cria tabela automaticamente
├── app/
│   ├── Dockerfile            # PHP 8.2 + Apache + PDO MySQL
│   └── src/
│       ├── index.php         # Interface web
│       ├── api.php           # API REST (cadastrar/listar/deletar)
│       └── config.php        # Conexão PDO com MySQL
└── web/
    └── nginx.conf            # Nginx na porta 8080 → proxy para PHP
```

## Como rodar

```bash
# 1. Suba os containers
docker-compose up --build -d

# 2. Aguarde ~15s para o MySQL inicializar

# 3. Acesse no navegador
http://localhost:8080
```

## Funcionalidades

- **Cadastrar** nome, sobrenome e endereço
- **Listar** todos os cadastros em tabela
- **Remover** registros individuais
- Dados persistidos em volume Docker (`db-data`)

## Portas

| Serviço | Porta interna | Porta exposta |
|---------|--------------|---------------|
| db (MySQL) | 3306 | 3306 |
| app (PHP+Apache) | 80 | — (interno) |
| web (Nginx) | 8080 | **8080** |
