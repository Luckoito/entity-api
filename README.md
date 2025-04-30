
# EntityAPI

Uma API RESTful universal desenvolvida em Laravel, capaz de cadastrar qualquer entidade com propriedades e dados dinâmicos. Ideal para aplicações genéricas, sistemas de metadata e modelagem de dados flexível.

## 📌 Objetivo

Permitir que o usuário crie **entidades personalizadas**, definindo suas propriedades e registrando instâncias dessas entidades, tudo via endpoints REST. A estrutura é extensível e preparada para cenários de alta escalabilidade.

---

## 🛠️ Tecnologias Utilizadas

- **Laravel** (Framework PHP - MVC)
- **SQLite** (banco de testes, facilmente substituível por MySQL)
- **Docker + Docker Compose** (ambiente containerizado)
- **PHP 8+**

---

## 🧱 Estrutura do Projeto

```
.
├── app/
│   ├── Http/
│   │   ├── Controllers/       # EntityController, PropertyController, InstanceController
│   │   └── Requests/          # Validação de requisições (EntityRequest, etc.)
│   ├── Models/                # Modelos: Entity, Property, Instance, InstanceData
│   └── Repositories/          # Lógica de persistência separada por entidade
├── routes/
│   └── api.php                # Rotas organizadas por prefixo
├── database/
│   ├── migrations/            # Todas as tabelas necessárias
│   └── database.sqlite        # Banco de dados local para testes
├── docker-compose.yml         # Orquestração dos containers
├── Dockerfile                 # Ambiente Laravel customizado
├── entrypoint.sh              # Script de setup
└── .env                       # Variáveis de ambiente
```

---

## 📚 Endpoints Disponíveis

### Entity

- `GET /entity/` — Listar todas
- `GET /entity/count` — Contar entidades
- `GET /entity/name/{name}` — Buscar por nome
- `GET /entity/id/{id}` — Buscar por ID
- `POST /entity/new` — Criar nova entidade
- `PUT /entity/rename` — Renomear
- `DELETE /entity/{id}` — Excluir

### Property

- `GET /property/`
- `GET /property/id/{id}`
- `GET /property/name/{name}`
- `GET /property/count`
- `POST /property/new`
- `PUT /property/rename`
- `DELETE /property/{id}`

### Instance

- `GET /instance/`
- `GET /instance/count`
- `GET /instance/entity/{name}`
- `GET /instance/id/{id}`
- `GET /instance/data`
- `POST /instance/new`
- `PUT /instance/update`
- `DELETE /instance/{id}`

---

## 🚀 Como Rodar o Projeto

1. **Clonar o repositório:**

```bash
git clone https://github.com/seu-usuario/entity-api.git
cd entity-api
```

2. **Dar permissão ao script de entrada:**

```bash
chmod +x entrypoint.sh
```

3. **Subir a aplicação com Docker Compose:**

```bash
docker compose up --build
```

A API estará disponível em `http://localhost`.

---

## ⚙️ Otimização e Escalabilidade

A tabela `instance_data` pode crescer muito conforme o uso da aplicação. Para garantir performance, foi projetada com possibilidade de:

- **Particionamento horizontal** por entidade ou por tipo de propriedade.
- **Indexação em colunas estratégicas**, como `instance_id`, `property_id` e `value`.

Isso permite a evolução do sistema sem perda de desempenho.

---

## 📌 Contribuição

Pull requests são bem-vindos! Para mudanças maiores, por favor abra uma issue antes para discutir o que você gostaria de alterar.

---

## 🧠 Autor

Desenvolvido como parte do **Desafio Final** do Bootcamp [Arquiteto(a) de Software].

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.
