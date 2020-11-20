# Desafio Back-End - Campeonato de Futebol da Before

A Before está organizando um campeonato de futebol de salão no final do ano entre os seus funcionários. Como serão vários times e em dias diferentes a diretoria solicitou o desenvolvimento de um sistema para controlar o campeonato.

A equipe de análise colheu as informações de como a diretoria espera que o sistema funcione e encaminhou à equipe de desenvolvimento para começar o desenvolvimento da aplicação.

Resumo da análise feita pela _Palmira_, uma das analistas envolvidas no projeto:

> Esta aplicação deverá representar um campeonato de futebol de salão. Cada time poderá conter no máximo 5 jogadores em campo incluindo o goleiro. Não haverá reservas, juiz ou bandeirinha. 
>
> A equipe de RH poderá cadastrar os jogadores como também distribuir os jogadores nos seus respectivos times. Uma vez escalado o jogador não poderá mudar de time, mas a equipe de RH poderá ajustar: o nome do jogador, número da sua camiseta e o nome do time se necessário. Não será possível cadastrar um jogador com CPF duplicado.
>
> Os jogos serão registrados após a realização dos mesmos, desta forma será necessário preencher os seguintes dados: Data da partida, Hora de início da partida, Hora de término da partida, times, cartões e o placar para a classificação final. Um detalhe importante é que poderá ser editado a partida caso tenha alguma informação incorreta.
>
> Caso algum jogador cometa faltas e/ou tenha recebido cartão vermelho, estes valores serão considerados como fator de desempate no final do campeonato. O critério de desempate será o seguinte: cada cartão vermelho corresponderá a 2 pontos e cada cartão amarelo corresponderá a 1 ponto, quem tiver menos pontos ganha.
>
> Por fim, a diretoria gostaria de exibir de forma automática o ranking dos times com melhor placar e também um ranking dos jogadores para premiação na festa.
>
> Contamos com a equipe para iniciarmos o desenvolvimento o quanto antes.
>
> Bom Trabalho!
Atenciosamente, Palmira.


Neste momento, você está escalado para trabalhar com a equipe de **back-end** e aplicar os seus conhecimentos no desenvolvimento da API dessa aplicação. A equipe de front-end irá cuidar de toda a parte do front para você, então não se preocupe!

## Sua API deverá ser capaz de:
1. Cadastrar, editar e listar os jogadores
- Nome*
- CPF*
- Número da camiseta*
2. Cadastrar, editar e listar os times
- Nome do time
- Jogadores
3. Cadastrar e editar as partidas
- Data*
- Horário início*
- Horário término*
- Times*
- Placar*
- Cartões
4. Listar a classificação dos times no campeonato
- Time
- Quantidade de gols

### Itens obrigatórios:

- Documentação dos endpoints, informando o payload e os possíveis retornos;

### Itens que podem ser implementados e acrescentam pontos:

- Cadastro de cartões por partida;
- Listar a classificação dos jogadores no campeonato;
- Ordenação das listagens;
- Filtros das listagens;
- Utilização de containers Docker;
- Implementação de Testes Unitários;
- Implementação de Testes de Integração;
- Autenticação na API;

##  Regras para o desenvolvimento da API:

- A arquitetura deverá respeitar as boas práticas do RestFull;
- A linguagem implementada deverá ser em PHP;
- A api deverá ser implementada usando o framework Laravel;
- Deverá usar um banco de dados relacional para armazenar os dados;

Estas regras são eliminatórias e o não cumprimento desclassifica o candidato.

## Como sua prova será avaliada:

- Correto funcionamento dos endpoints;
- Tratamento de erros;
- Implementação de padrões de projeto (design patterns, SOLID, etc); 
- Documentação dos endpoints;
- Código limpo e organizado;
- Modelagem do banco de dados;

## Duração da prova

A prova poderá ser entregue até:

**23/11/2020 às 23:59:59**

## Como entregar a prova

Antes de começar o desenvolvimento, faça um fork do repositório do avaliador e faça um clone do repositório forkeado no seu ambiente de desenvolvimento.
Após terminar o desenvolvimento, submeta sua prova ao repositório forkeado e abra uma Pull Request solicitando a inclusão do seu código ao repositório do avaliador.
Resumindo:

1. Fork
2. Clone
3. Desenvolvimento
4. Push para o Fork
5. Pull Request do Fork para o repositório do seu Avaliador

Seguindo estes passos não tem como errar, mas caso algo aconteça contacte o seu avaliador!

Boa sorte! Aguardamos pela sua prova :smile:.
