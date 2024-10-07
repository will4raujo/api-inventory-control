# Instruções para Configuração do Projeto Laravel

## Pré-requisitos

Antes de iniciar, certifique-se de que você tenha o **Laravel Herd** instalado em sua máquina. Caso não tenha, você pode encontrá-lo na [documentação oficial do Laravel Herd](https://laravel.com/docs/8.x/herd).

## Passos para Configuração

1. **Instalar o Laravel Herd**
   - Siga as instruções na [documentação do Laravel Herd](https://laravel.com/docs/8.x/herd) para instalar o Laravel Herd.

2. **Baixar o Projeto**
   - Clone o repositório do projeto do Git:
     ```bash
     git clone https://seu-repositorio.git
     ```
   - Cole a pasta do projeto dentro da pasta de configuração do Laravel Herd.

3. **Configurar o Docker**
   - Acesse a pasta do projeto na linha de comando.
   - Execute o seguinte comando para iniciar o Docker:
     ```bash
     docker-compose up
     ```

4. **Configurar as Variáveis de Ambiente**
   - Abra o arquivo `.env` e configure as seguintes variáveis:
     ```plaintext
     DB_CONNECTION=mysql
     DB_HOST=<insira_o_host_aqui>
     DB_PORT=<insira_o_porto_aqui>
     DB_DATABASE=inventory_db
     DB_USERNAME=inventory_user
     DB_PASSWORD=inventory_password
     
     APP_URL=<insira_o_endereço_fornecido_pelo_herd>
     ```

5. **Criar as Tabelas**
   - Execute o comando de migração para criar as tabelas no banco de dados:
     ```bash
     php artisan migrate
     ```

6. **Rodar as Seeds**
   - Execute as seeds na seguinte ordem:
     - **Obrigatório:**
       ```bash
       php artisan db:seed UsersRolesTableSeeder
       ```
     - **Opcional:**
       ```bash
       php artisan db:seed UsersTableSeeder
       php artisan db:seed CategoriesTableSeeder
       php artisan db:seed SuppliersTableSeeder
       php artisan db:seed ProductsTableSeeder
       ```

## Credenciais dos Usuários
- Caso tenha rodado a seed de usuários, os emails e senhas são:
  - `admin@example.com` - Senha: `password`
  - `manager@example.com` - Senha: `password`
  - `user@example.com` - Senha: `password`

## Observações
- Certifique-se de que o banco de dados está corretamente configurado no arquivo `.env` do projeto antes de rodar as migrações e as seeds.
- Caso encontre algum erro durante a execução dos comandos, verifique as mensagens de erro para solucionar possíveis problemas.

## Conclusão
Após seguir todos os passos acima, o projeto deve estar configurado e pronto para uso. Caso tenha dúvidas, sinta-se à vontade para entrar em contato.
