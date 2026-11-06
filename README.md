# Mapa Cultural de Castro — Prefeitura Municipal de Castro (PR)

Repositório da instalação oficial da plataforma **Mapas Culturais** para a **Prefeitura Municipal de Castro - PR**, estruturado a partir do [Mapas Culturais Base Project](https://github.com/mapasculturais/mapasculturais-base-project) e utilizando o core [Mapas Culturais v7.8+ (BaseV2)](https://github.com/mapasculturais/mapasculturais).

---

## Arquitetura do Projeto

O projeto utiliza **Docker** e **Docker Compose** para orquestrar todos os serviços necessários tanto em desenvolvimento local quanto no servidor de produção:

- **`mapasculturais` / `mapas`**: Aplicação principal PHP 8.3 + Vue 3 (Tema `BaseV2`), baseada na imagem oficial `hacklab/mapasculturais:latest`.
- **`db`**: Banco de dados geoespacial **PostgreSQL 16 + PostGIS**.
- **`redis` / `sessions`**: Cache de aplicação e armazenamento de sessões em memória (**Redis 6**).
- **`nginx`** *(Produção)*: Servidor web / proxy reverso com suporte a SSL/TLS (Let's Encrypt) e regras de segurança para uploads.
- **`mailhog`** *(Desenvolvimento)*: Servidor SMTP local para captura e visualização de e-mails de teste (`http://localhost:8025`).

### Plugins Pré-configurados (Submódulos Git)
- **[MultipleLocalAuth](https://github.com/mapasculturais/plugin-MultipleLocalAuth)**: Autenticação local por e-mail/CPF e senha, recuperação de senha, reCAPTCHA e integração opcional com login social (Google, Facebook, LinkedIn) e **Gov.br**.
- **[SpamDetector](https://github.com/mapasculturais/plugin-SpamDetector)**: Detecção e bloqueio automático de spam em cadastros de agentes, espaços, eventos, projetos e oportunidades.

---

## Estrutura de Diretórios

- `.env_sample` — Modelo de variáveis de ambiente (já pré-configurado com dados e coordenadas de Castro - PR).
- `docker/` — Dockerfile e configurações da aplicação:
  - `docker/common/config.d/0.main.php` — Nome do site (`Mapa Cultural de Castro`), descrição, logo e idioma.
  - `docker/common/config.d/maps.php` — Coordenadas centrais de Castro/PR (`-24.7911, -50.0119`) e níveis de zoom.
  - `docker/common/config.d/plugins.php` — Lista de plugins habilitados.
  - `docker/production/` — Configurações exclusivas de produção (`authentication.php`, `nginx.conf`, `nginx-ssl.conf`).
  - `docker/db/dump.sql` — Dump inicial do banco de dados.
- `dev/` — Ambiente de desenvolvimento local:
  - `dev/docker-compose.yml` — Serviços de desenvolvimento (`mapas`, `db`, `redis`, `mailhog`).
  - `dev/config.d/` — Configurações locais de debug, cache, logs, e-mail e autenticação (`Fake` ou `MultipleLocalAuth`).
  - `dev/start.ps1` / `dev/stop.ps1` / `dev/logs.ps1` / `dev/bash.ps1` / `dev/psql.ps1` — Scripts PowerShell para Windows.
  - `dev/start.sh` / `dev/bash.sh` / `dev/psql.sh` / `dev/shell.sh` / `dev/watch.sh` — Scripts Bash para Linux / WSL / macOS.
- `plugins/` — Plugins do projeto (`MultipleLocalAuth`, `SpamDetector`, `SamplePlugin`).
- `themes/` — Pasta para temas customizados do município.
- `scripts/` — Scripts automatizados de backup diário/mensal do banco de dados e arquivos estáticos.

---

## Ambiente de Desenvolvimento Local

### Pré-requisitos
- **Git**
- **Docker** e **Docker Compose** (Docker Desktop no Windows/Mac ou Docker Engine no Linux)

### 1. Subindo o ambiente no Windows (PowerShell)

Para construir a imagem e iniciar os containers em segundo plano:
```powershell
.\dev\start.ps1 -Build -Detach
```

Para acompanhar os logs de inicialização (na primeira execução o container instala os pacotes front-end via `pnpm` e aplica as migrações do banco):
```powershell
.\dev\logs.ps1 mapas
```

Outros comandos úteis no PowerShell:
```powershell
.\dev\stop.ps1   # Para e remove os containers de desenvolvimento
.\dev\bash.ps1   # Abre o terminal bash dentro do container da aplicação
.\dev\psql.ps1   # Abre o console do PostgreSQL (banco mapas)
```

### 2. Subindo o ambiente no Linux / WSL / macOS (Bash)

```bash
cd dev
./start.sh --build
```

### 3. Acessando a Plataforma Localmente

- **Plataforma Mapas Culturais**: [http://localhost/](http://localhost/)
- **MailHog (Caixa de entrada de e-mails de teste)**: [http://localhost:8025/](http://localhost:8025/)
- **Banco PostgreSQL/PostGIS**: `localhost:5432` (usuário: `mapas`, senha: `mapas`, banco: `mapas`)

#### Credenciais de Super Administrador Inicial
No modo de desenvolvimento padrão (`auth.provider = Fake`), há uma barra superior/tela de autenticação rápida onde você pode logar com 1 clique no usuário **`Admin@local`**.
Caso altere para o provedor `MultipleLocalAuth`, utilize:
- **E-mail**: `Admin@local`
- **Senha**: `mapas123`

---

## Implantação em Servidor de Produção (Linux)

### 1. Clonar o repositório no servidor
```bash
cd /srv
sudo git clone --recursive https://github.com/adrianolerner/mapas-castro.git
cd mapas-castro
```

### 2. Configurar variáveis de ambiente (`.env`)
```bash
cp .env_sample .env
nano .env
```
Preencha no `.env`:
- `BASE_URL=https://mapacultural.castro.pr.gov.br/` (domínio oficial)
- `DB_PASS` (senha segura para o PostgreSQL)
- `AUTH_SALT` (string aleatória longa de segurança)
- `GOOGLE_RECAPTCHA_SITEKEY` e `GOOGLE_RECAPTCHA_SECRET` (chaves reCAPTCHA v2 do domínio)
- `MAILER_TRANSPORT` e `MAILER_FROM` (credenciais SMTP da Prefeitura)

### 3. Configurar Certificado SSL (Let's Encrypt)
Caso o próprio servidor gerencie o SSL (sem proxy reverso externo):
1. Edite `init-letsencrypt.sh` informando o domínio em `domains=(mapacultural.castro.pr.gov.br)` e o `email`, e remova a linha `exit;` inicial.
2. Execute em modo de teste (`staging=1`):
   ```bash
   sudo ./init-letsencrypt.sh
   ```
3. Com o teste validado, altere `staging=0` em `init-letsencrypt.sh` e execute novamente:
   ```bash
   sudo ./init-letsencrypt.sh
   ```
4. No arquivo `docker-compose.yml`, comente o volume `nginx.conf` (sem SSL) e descomente as linhas da **versão com ssl** (`nginx-ssl.conf` e volumes de certificados).

### 4. Iniciar e Atualizar em Produção
```bash
# Iniciar em background
sudo ./start.sh

# Visualizar logs
sudo ./logs.sh

# Atualizar plataforma (git pull + submodules + rebuild Docker)
sudo ./update.sh
```

---

## Rotina de Backups em Produção

Os scripts na pasta `scripts/` realizam o backup completo do banco PostgreSQL e dos arquivos enviados pelos usuários (`docker-data/private-files`, `docker-data/public-files`, `docker-data/logs` e `.env`).

Exemplo de configuração no `crontab -e`:
```cron
# Dump diário do banco à meia-noite
00 00 * * * bash /srv/mapas-castro/scripts/postgres-dump.sh /var/backups/mapas-castro/

# Histórico diário (dia do mês)
00 01 * * * bash /srv/mapas-castro/scripts/backup-day.sh /var/backups/mapas-castro/

# Histórico mensal (YYYY-MM)
00 02 1 * * bash /srv/mapas-castro/scripts/backup-mon.sh /var/backups/mapas-castro/

# Backup de uploads, arquivos privados, logs e .env
00 03 * * * bash /srv/mapas-castro/scripts/backup-files.sh /srv/mapas-castro /var/backups/mapas-castro/
```

---

## Licença

Software livre licenciado sob [GPLv3](LICENCE).
