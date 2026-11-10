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

### Plugins Pré-configurados (Integrados em `plugins/`)
- **[MultipleLocalAuth](https://github.com/mapasculturais/plugin-MultipleLocalAuth)**: Autenticação local por e-mail/CPF e senha, recuperação de senha, reCAPTCHA e integração opcional com login social (Google, Facebook, LinkedIn) e **Gov.br**.
- **[SpamDetector](https://github.com/mapasculturais/plugin-SpamDetector)**: Detecção e bloqueio automático de spam em cadastros de agentes, espaços, eventos, projetos e oportunidades.
- **SamplePlugin**: Plugin de personalização visual, regras de cache e rotas auxiliares da instalação de Castro - PR.

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

## Histórico de Modificações e Melhorias (em relação ao Projeto Base Original)

Abaixo estão documentadas todas as correções, otimizações de desempenho e personalizações aplicadas nesta instalação sobre o repositório original (`mapasculturais/mapasculturais-base-project`):

### 1. Identidade Visual, Textos e Geolocalização de Castro - PR
- **Motivo / Melhoria**: Personalização da plataforma para a Prefeitura Municipal de Castro (PR), incluindo a logo oficial da Prefeitura no cabeçalho e rodapé (com ajuste de proporção horizontal), textos de boas-vindas customizados, substituição das imagens dos 5 cards de entidades (*Oportunidades, Eventos, Espaços, Agentes e Projetos*) e dos fundos das seções da Home por fotografias reais de pontos históricos e ações culturais de Castro (Moinho da Castrolanda, Museu do Tropeiro, Fazenda Capão Alto, Centro Histórico e Ponte Ferroviária sobre o Rio Iapó), além da centralização padrão dos mapas nas coordenadas do município (`-24.7911, -50.0119`, zoom `13`).
- **Arquivos modificados / adicionados**:
  - `docker/common/config.d/0.main.php`
  - `docker/common/config.d/maps.php`
  - `plugins/SamplePlugin/Plugin.php`
  - `plugins/SamplePlugin/assets/img/logo.png`
  - `plugins/SamplePlugin/assets/img/home/*.jpg` (`agentes.jpg`, `espacos.jpg`, `eventos.jpg`, `oportunidades.jpg`, `projetos.jpg`, `fundo1.jpg` a `fundo5.jpg`)
  - `.env_sample`

### 2. Compatibilidade do Dump Inicial com PostgreSQL 16 + PostGIS (`postgis:16-master`)
- **Motivo / Melhoria**: O arquivo `dump.sql` original continha instruções para a extensão `postgis_tiger_geocoder` e o schema `tiger` (geocodificador exclusivo dos EUA), que foram removidos na imagem oficial `postgis/postgis:16-master`, interrompendo a importação inicial das tabelas do banco na linha 85. As referências obsoletas ao schema `tiger` foram removidas para permitir a inicialização limpa do banco.
- **Arquivos modificados**:
  - `docker/db/dump.sql`

### 3. Correção de Salvamento de Senha, Validação de E-mail e Fluxo de Cadastro (`MultipleLocalAuth`)
- **Motivo / Melhoria**:
  - Na branch `feature/login-basev2` do plugin `MultipleLocalAuth`, o método `doRegister()` chamava `$user->save()` sem o parâmetro `$flush = true` logo antes de encerrar a requisição com `$app->halt()`. Com isso, os metadados `localAuthenticationPassword` (hash da senha), `tokenVerifyAccount` (token de ativação) e `accountIsActive` não eram persistidos na tabela `user_meta`, impedindo o login subsequente do usuário e invalidando o link de confirmação de e-mail.
  - O token de validação era gerado recortando uma substring de um hash `bcrypt` (podendo conter caracteres `.` e `/` na URL); foi substituído por `bin2hex(random_bytes(16))`.
  - O login automático após cadastro (`loginOnRegister`) agora respeita a flag `AUTH_CONFIRM_EMAIL` (`userMustConfirmEmailToUseTheSystem`), exibindo a mensagem de envio de confirmação quando a validação por e-mail estiver ativa.
  - Na rota `GET(auth.confirma-email)`, foi adicionado o `return;` ausente ao detectar token inválido (que causava erro fatal 500 ao acessar `$usermeta->owner` em `null`) e corrigido o texto da view `confirm-email.php` (que exibia mensagem de "Alteração de senha" em vez de "E-mail confirmado com sucesso").
- **Arquivos modificados**:
  - `plugins/MultipleLocalAuth/Provider.php`
  - `plugins/MultipleLocalAuth/views/auth/confirm-email.php`
  - `docker/production/config.d/authentication.php`
  - `dev/config.d/auth.php`
  - `dev/config.d/mailer.php`

### 4. Incorporação dos Plugins `MultipleLocalAuth` e `SpamDetector` e seus Assets Compilados
- **Motivo / Melhoria**: Os plugins `MultipleLocalAuth` e `SpamDetector` foram incorporados diretamente à árvore principal do repositório Git (deixando de depender de submódulos externos) e seus arquivos `.gitignore` foram ajustados para versionar os arquivos CSS/JS compilados (`assets/css/plugin-MultiplLocalAuth.css`, `assets/css/plugin-SpamDetector.css` e `assets/js/app.js`), evitando erros `Asset not found` ao mapear os volumes em produção.
- **Arquivos modificados**:
  - `plugins/MultipleLocalAuth/.gitignore`
  - `plugins/SpamDetector/.gitignore`
  - `docker-compose.yml`

### 5. Correção de Permissões de Sessão e Isolamento do Volume Redis
- **Motivo / Melhoria**: No `docker-compose.yml` original, o container `mapasculturais` e o container `sessions` (Redis) compartilhavam o mesmo diretório `./docker-data/sessions`, fazendo o Redis alterar o dono da pasta para UID `999` e gerando erro `Permission denied (13)` para o `www-data` (UID `33`) do PHP, além de a pasta `/var/www/var/sessions` na imagem base pertencer a `root`. As permissões foram corrigidas no `Dockerfile` e as sessões foram direcionadas exclusivamente via protocolo TCP para o Redis (`tcp://sessions:6379` em produção e `tcp://redis:6379` em desenvolvimento).
- **Arquivos modificados**:
  - `docker/Dockerfile`
  - `docker-compose.yml`
  - `dev/docker-compose.yml`

### 6. Atualização Imediata de Listas e Carrosséis (Correção de Cache HTTP e `pcache`)
- **Motivo / Melhoria**:
  - A classe `API.js` do core realizava chamadas `fetch()` com `cache: 'force-cache'`, fazendo o navegador exibir listas vazias ou desatualizadas em `#list` (Espaços, Agentes, Eventos, Oportunidades e Projetos) mesmo após o cadastro de novos itens. Foi adicionado um listener global `beforeFetch` no plugin da instalação para substituir `force-cache` por `no-cache`.
  - Foi habilitado `'app.recreateCacheImmediately' => true` para que o cache de permissões (`pcache`) seja atualizado instantaneamente ao criar ou publicar qualquer entidade, e removida a exigência padrão de selo verificador manual nos carrosséis da Home (`home.opportunities.filter` e `home.featured.filter`).
- **Arquivos modificados**:
  - `plugins/SamplePlugin/Plugin.php`
  - `docker/common/config.d/0.main.php`
  - `dev/config.d/cache.php`

### 7. Otimização de Performance em Produção (Redução de CPU e Tempo de Resposta em 12x)
- **Motivo / Melhoria**:
  - **Redução de CPU em repouso (de ~19% para 0.01%)**: No `.env_sample` original, os intervalos `PENDING_PCACHE_RECREATION_INTERVAL=1` e `JOBS_INTERVAL=1` disparavam 2 processos PHP CLI completos por segundo (`120` inicializações por minuto) em loop contínuo. Os intervalos foram ajustados para `60s` e `15s` respectivamente, e o `LOG_LEVEL` de produção ajustado para `WARNING`.
  - **Eliminação do bloqueio de 6–7s por página no `AssetManager`**: Em modo `production`, o gerenciador de assets padrão invocava o processo Node.js `terser --source-map` e `uglifycss` de forma síncrona dentro da requisição HTTP a cada nova combinação de componentes Vue (~1,4 MB de JS), travando o carregamento de páginas em **6,6 a 7,1 segundos**. O `themes.assetManager` foi configurado para agrupar os arquivos instantaneamente (`awk 1 {IN} > {OUT}` em ~3ms) delegando a compressão ao **Gzip nativo do Nginx**.
  - **Ativação de Gzip no Nginx**: Adicionada compressão `gzip` para `JS`, `CSS`, `JSON`, `XML` e `SVG` no Nginx de produção, reduzindo o tráfego do bundle `components.js` de **1,37 MB** para **~280 KB**.
  - **Tuning do PHP 8.3 OPcache e Realpath Cache**: Ampliados os limites no `Dockerfile` (`opcache.memory_consumption=256`, `opcache.max_accelerated_files=32531`, `realpath_cache_size=16384k`) para comportar todos os >15.000 arquivos PHP do projeto em memória.
  - **Otimização das Imagens da Home**: As fotografias de fundo e dos cards foram redimensionadas e convertidas para JPEG progressivo otimizado, reduzindo o peso total das imagens da Home de **21,1 MB** para **1,8 MB** sem perda visual.
- **Arquivos modificados**:
  - `docker/common/config.d/0.main.php`
  - `docker/production/nginx.conf`
  - `docker/production/nginx-ssl.conf`
  - `docker/Dockerfile`
  - `docker-compose.yml`
  - `.env_sample`
  - `plugins/SamplePlugin/assets/img/home/*.jpg`

### 8. Suporte Multiplataforma (Windows / Linux) e Padronização deScripts
- **Motivo / Melhoria**: Criação de scripts PowerShell (`dev/*.ps1`) para facilitar o desenvolvimento no Windows, atualização dos scripts `.sh` da antiga sintaxe `docker-compose` (v1) para `docker compose` (v2) e adição do arquivo `.gitattributes` forçando fim de linha `LF` em scripts `.sh`, `.php`, `.sql`, `.yml` e `Dockerfile` para evitar erros de execução no Linux quando editados no Windows.
- **Arquivos modificados / adicionados**:
  - `.gitattributes`, `.dockerignore`, `.gitignore`
  - `dev/start.ps1`, `dev/stop.ps1`, `dev/logs.ps1`, `dev/bash.ps1`, `dev/psql.ps1`
  - `start.sh`, `stop.sh`, `restart.sh`, `logs.sh`, `bash.sh`, `psql.sh`, `update.sh`, `init-letsencrypt.sh`, `scripts/*.sh`, `dev/*.sh`

---

## Licença

Software livre licenciado sob [GPLv3](LICENCE).
