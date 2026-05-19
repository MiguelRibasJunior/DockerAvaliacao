## Como rodar
docker --version
abaixe o git se estiver na VM 

```bash
# 1. Suba os containers
docker-compose up --build -d

# 2. Aguarde ~15s para o MySQL inicializar

# 3. Acesse no navegador
http://localhost:8080
digite 8080 não 80 (aviso)
```

## Funcionalidades

- **Cadastrar** nome, sobrenome e endereço
- **Listar** todos os cadastros em tabela
- **Remover** registros individuais
- Dados persistidos em volume Docker (`db-data`)

## Portas
 db (MySQL) | 3306 | 3306 |
 app (PHP+Apache) | 80 | — (interno) |
 web (Nginx) | 8080 | **8080** |

