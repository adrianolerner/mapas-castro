# Manual de Operação — Administrador e Super Administrador
## Mapa Cultural de Castro - PR (`https://mapacultural.castro.pr.gov.br/`)

Este manual orienta a equipe da **Prefeitura Municipal de Castro / Secretaria Municipal de Cultura** e o suporte de **TI** nas operações diárias de administração da plataforma **Mapas Culturais (BaseV2)**.

---

## 1. Acesso e Níveis de Permissão

### 1.1 Como acessar o sistema
1. Acesse **`https://mapacultural.castro.pr.gov.br/autenticacao/`**.
2. Informe seu **E-mail** ou **CPF** e sua **Senha** e clique em **Entrar**.
3. No canto superior direito, clique no avatar/nome do seu usuário para acessar o **Painel de Controle** (`/painel/`).

> **Dica de segurança**: Para alterar sua senha, acesse no menu lateral **Outras opções → Conta e Privacidade** (`/panel/my-account`).

### 1.2 Hierarquia de Papéis (Funções)
No Mapas Culturais existem 4 níveis administrativos:

| Papel | Quem deve receber | O que pode fazer |
| :--- | :--- | :--- |
| **Super Administrador de Rede** (`saasSuperAdmin`) | Equipe de TI / Coordenação Geral | Controle total da plataforma. Único nível que pode atribuir ou remover papéis globais (`saasAdmin`, `saasSuperAdmin`, `superAdmin` e `admin`). |
| **Administrador de Rede** (`saasAdmin`) | Gestores Gerais da Cultura | Gerencia todas as entidades, oportunidades, selos, usuários e funções customizadas (`Funções de usuários`), sem poder promover outros *Administradores de Rede*. |
| **Super Administrador** (`superAdmin`) | Direção / Chefia da Secretaria de Cultura | Administra todos os conteúdos e selos da instalação e pode nomear **Administradores** comuns. |
| **Administrador** (`admin`) | Servidores operacionais da Cultura | Cria e gerencia editais/oportunidades, avalia e valida inscrições, aplica selos certificadores e modera agentes, espaços, eventos e projetos. Não altera permissões de usuários. |

---

## 2. Estrutura do Painel de Controle (`/painel/`)

Ao entrar no Painel, o menu é dividido em blocos:

- **Menu do Painel de Controle**:
  - **Painel de Controle**: Visão geral com contadores rápidos das suas entidades, rascunhos e notificações.
- **Editais e oportunidades**:
  - **Minhas oportunidades**: Criação e gestão de editais, chamamentos públicos, prêmios e credenciamentos.
  - **Minhas inscrições**: Acompanhamento de inscrições feitas pelo seu agente.
  - **Minhas avaliações / Minhas validações** *(aparecem quando você é designado avaliador ou validador de uma fase)*.
- **Gerenciamento de entidades**:
  - **Meus Agentes**, **Meus Espaços**, **Meus Eventos**, **Meus Projetos**: Entidades criadas ou gerenciadas diretamente pela sua conta.
- **Outras opções**:
  - **Conta e Privacidade**: Troca de senha, e-mail de acesso, termos de uso aceitos (LGPD) e dados da conta.
- **Administração** *(exclusivo para perfis Administradores)*:
  - **Gestão de Selos**: Criação e aplicação de selos oficiais e certificadores.
  - **Gestão de usuários**: Consulta de cadastros, alteração de e-mail, bloqueio/ativação e atribuição de papéis.
  - **Funções de usuários** *(visível a partir de `saasAdmin`)*: Criação de papéis personalizados com permissões específicas.

---

## 3. Gestão de Usuários e Permissões *(Super Administrador / TI)*

Acesse **Administração → Gestão de usuários**.

### 3.1 Buscar um usuário e visualizar detalhes
1. Utilize a barra de busca para localizar o usuário por **nome**, **e-mail** ou **CPF**.
2. Clique sobre o usuário para abrir sua tela de detalhes (`user-detail`).

### 3.2 Conceder ou remover função administrativa
1. Na tela de detalhes do usuário, localize a seção de **Funções / Papéis**.
2. Selecione o papel desejado (por exemplo, **Administrador** para um servidor da Secretaria de Cultura) e salve.
3. Para revogar o acesso administrativo, remova o papel atribuído. O usuário voltará a ser um agente cultural comum, mantendo seus cadastros.

> **Importante**: Apenas o **Super Administrador de Rede** consegue atribuir `Administrador de Rede` ou `Super Administrador de Rede`. Um `Super Administrador` comum consegue atribuir apenas `Administrador`.

### 3.3 Suporte ao cidadão (Alteração de e-mail / redefinição de senha)
- Caso um agente cultural perca acesso ao e-mail cadastrado, o administrador pode atualizar o e-mail na ficha do usuário em **Gestão de usuários**, permitindo que o cidadão utilize o fluxo **"Esqueci minha senha"** em `/autenticacao/recover/` com o novo endereço.

---

## 4. Gestão de Selos Oficiais (`Gestão de Selos`)

Os **Selos** funcionam como certificações oficiais da Prefeitura (ex.: *"Espaço Cultural Oficial de Castro"*, *"Ponto de Cultura Certificado"*, *"Agente Verificado"*).

### 4.1 Criar um novo selo
1. Acesse **Administração → Gestão de Selos**.
2. Clique em **Criar Selo**.
3. Preencha **Nome**, **Descrição curta**, carregue a imagem/brasão do selo e defina o período de validade (se houver).
4. Altere o status para **Publicado** para que ele fique disponível.

### 4.2 Aplicar um selo em um Agente, Espaço, Evento ou Projeto
1. Navegue até a página pública do item desejado (por exemplo, um Espaço em `/espacos/`).
2. Abra a página do item e clique na opção **Aplicar Selo** (ou na aba de edição do item, disponível para administradores).
3. Selecione o selo oficial desejado e confirme.
4. Na página de busca (ex.: `/espacos/`), usuários podem marcar o filtro **"Espaços oficiais"** para listar apenas os itens certificados com selos verificadores.

---

## 5. Criação e Gestão de Editais / Oportunidades

O módulo de **Oportunidades** é usado para conduzir editais (como Lei Paulo Gustavo, PNAB, Fomento Municipal, Credenciamentos e Chamamentos Públicos).

### 5.1 Criando uma Oportunidade (Edital)
1. Acesse **Editais e oportunidades → Minhas oportunidades** e clique em **Criar Oportunidade**.
2. Escolha o **Tipo** (Edital, Chamamento, Prêmio, Seleção, etc.) e o **Projeto** ou **Agente** proponente (ex.: *Secretaria Municipal de Cultura de Castro*).
3. Preencha as informações principais:
   - **Título** e **Descrição curta**;
   - **Descrição detalhada**, regulamento e **Arquivos para download** (anexe o PDF oficial do Edital, anexos e modelos de declaração);
   - **Imagem de capa / banner** e **Áreas de atuação**.

### 5.2 Configurando as Fases do Edital
Um edital no Mapas Culturais funciona por **fases sequenciais**:
1. **Fase de Inscrição**:
   - Defina **Data e hora de início** e **Data e hora de término** das inscrições.
   - Configure o **Formulário de Inscrição** (campos de texto, seleção, envio de documentos PDF, links, áudio/vídeo, etc.).
   - Defina regras como: exigir CPF/CNPJ, quantidade máxima de inscrições por agente, categorias/módulos de inscrição e cotas.
2. **Fase de Avaliação** *(Documental, Técnica, Simples ou Contínua)*:
   - Escolha o **Método de Avaliação** (ex.: *Avaliação Técnica* por critérios e notas de 0 a 10, ou *Avaliação Documental* de habilitação/inabilitação).
   - Cadastre os **Critérios de Avaliação** e seus respectivos pesos.
   - Adicione os **Avaliadores** (agentes cadastrados na plataforma que terão acesso à aba *Minhas avaliações*) e distribua as inscrições entre eles.
3. **Fase de Recurso** *(opcional, mas recomendada em editais públicos)*:
   - Permite que proponentes inabilitados ou com nota contestada enviem recurso dentro do prazo configurado.
4. **Fases Posteriores (Execução / Plano de Trabalho / Prestação de Contas)**:
   - Podem ser encadeadas para os projetos selecionados enviarem relatórios de cumprimento e comprovantes.

### 5.3 Publicação e Acompanhamento
- Enquanto estiver montando o formulário, mantenha o edital como **Rascunho**.
- Antes de publicar, faça uma **inscrição de teste** (ou revise a pré-visualização do formulário) para conferir campos obrigatórios.
- Ao mudar o status para **Publicado**, o edital aparece automaticamente em `/oportunidades/` e no carrossel da página inicial.

### 5.4 Exportação de Resultados e Relatórios
- Dentro da página de gerenciamento da oportunidade (aba **Inscrições**):
  - É possível filtrar inscrições por status (*Enviada*, *Selecionada*, *Suplente*, *Inabilitada*, *Rascunho*).
  - Utilize o botão **Exportar** para baixar planilhas (`.xlsx` / `.csv`) com todos os dados inscritos, notas de avaliação e pareceres — útil para publicação de listas no Diário Oficial do Município.
  - Também é possível gerar o **Consolidado de Avaliações** e publicar o resultado preliminar/final diretamente na oportunidade.

---

## 6. Moderação e Gestão de Conteúdos Públicos (Agentes, Espaços, Eventos e Projetos)

Como Administrador, você tem permissão para editar, publicar, ocultar ou mover para a lixeira qualquer entidade que viole os termos de uso ou contenha dados incorretos.

### 6.1 Fluxo típico de moderação
1. Acesse a listagem pública (`/agentes/`, `/espacos/`, `/eventos/`, `/projetos/`).
2. Ao abrir qualquer ficha, o Administrador visualiza o botão **Editar** no topo.
3. **Ações comuns**:
   - **Corrigir localização no mapa**: Editar o espaço/evento e ajustar o pino no mapa de Castro para que apareça na posição exata.
   - **Despublicar / Rascunho / Lixeira**: Caso haja conteúdo impróprio, spam ou duplicado, altere o status para **Rascunho** ou **Mover para lixeira**.
   - **Transferir propriedade**: Um administrador pode vincular outro agente como responsável ou administrador auxiliar de um Espaço, Evento ou Projeto (na aba de **Agentes Relacionados** / permissões da entidade).

---

## 7. Boas Práticas de Operação e Segurança

1. **Contas nominais para a equipe**: Evite compartilhar uma única senha entre vários servidores. Crie uma conta individual para cada servidor e atribua o papel de **Administrador** (`admin`) apenas pelo tempo necessário.
2. **Testes antes de publicar editais**: Sempre configure prazos e campos obrigatórios com antecedência. Alterar campos obrigatórios depois que já existem inscrições enviadas pode gerar inconsistência nos dados exportados.
3. **Prazos de encerramento**: Lembre-se de que o horário do servidor está configurado para **`America/Sao_Paulo` (horário de Brasília)**. Verifique sempre os minutos finais (ex.: `23:59`) ao configurar datas de encerramento de editais.
4. **Denúncias e Spam**: O plugin `SpamDetector` atua automaticamente no bloqueio de cadastros automatizados, mas denúncias feitas por cidadãos (botão **Denúncia** nas páginas públicas) chegam por e-mail/notificação aos administradores para análise.
5. **Backups**: Os backups do banco de dados e arquivos enviados (`docker-data/private-files` e `docker-data/public-files`) são executados no servidor conforme os scripts da pasta `scripts/`. Antes de grandes edições em massa ou alterações estruturais no servidor, certifique-se de que o backup diário foi gerado.
