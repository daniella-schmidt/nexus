# Análise e Comentários de POO em PHP

### Conceitos de POO Aplicados no Projeto:
    ## 1. Visibilidade (public/private/protected)
        - Aplicado em todas as classes (Database, User, Route, Session, etc.)
        - Propriedades privadas para encapsulamento de dados
        - Métodos públicos para interfaces externas
        - Métodos protegidos para herança
    ## 2. Encapsulamento
        - Classe Session: Encapsula operações de sessão
        - Classe Database: Protege conexão com banco
        - Classe User: Propriedades privadas com getters/setters
        - Classe Router: Propriedade privada $routes
    ## 3. Herança
        - User, Route, Vehicle herdam de Database
        - Todos os Controllers herdam de BaseController
        - Classe abstrata Model serve como base
    ## 4. Polimorfismo
        - Método save() em User (create/update)
        - Métodos sobrescritos em subclasses
        - Interface comum através de herança
    ## 5. Associação
        - UserRepository associa com User para autenticação
        - Controllers associam com Repositories
        - BaseController associa com Session
    ## 6. Agregação
        - Controllers agregam Repositories (UserController -> UserRepository)
        - BaseController agrega Session
        - Repositories agregam Database
    ## 7. Composição
        - Database compõe DatabaseConfig
        - Classes utilizam outras como partes essenciais
    ## 8. Abstração
        - Classe abstrata Model define operações comuns
        - Interfaces implícitas através de métodos comuns
        - Separação entre interface e implementação
    # 9. Interfaces
        - RepositoryInterface define contrato para repositórios
        - UserRepository implementa RepositoryInterface
        - Padroniza métodos CRUD (findAll, findById, create, update, delete)
        - Permite polimorfismo entre diferentes tipos de repositório
    # 10. PDO
        - Database utiliza PDO para conexões seguras
        - Prepared statements previnem SQL injection
        - Interface consistente para diferentes bancos de dados
        - Tratamento adequado de tipos de dados (PARAM_INT, PARAM_STR, etc.)
