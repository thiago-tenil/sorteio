Sorteio
=======

Aplicação simples que sorteia um nome de uma tabela de nomes do banco de dados para um prêmio específico também adicionado em uma tabela de prêmios. Os nomes sorteados ficam armazenados no banco, mantendo um histórico com o nome do ganhador e o prêmio adquirido. A aplicação ainda permite sortear outro nome (se o ganhador não quiser o prêmio, por exemplo) ou eliminar o nome sorteado (caso o ganhador não esteja mais presente).
Construído com PHP utilizando Slim Framework, Sqlite, jQuery e Twitter Bootstrap e foi utilizado para o sorteio dos prêmios da Web Dev Summit 2015.

## Instalação

Pelo terminal, entre no diretório do seu projeto e instale o composer, da seguinte forma:
```
curl -s https://getcomposer.org/installer | php
```

Agora faça o download do Slim utilizando o composer assim:
```
php composer.phar install
```

## Estrutura de diretórios e arquivos
```
/css      - Arquivos de estilo
/img      - Imagem utilizada na aplicação
/js       - Arquivos de javascript
/model    - Banco de dados da aplicação
/vendor   - Arquivos do framework Slim
/views    - Tela da aplicação
index.php - Arquivo principal da aplicação
```

## Tabelas e campos do banco de dados
```
# participantes - Tabela que armazena os dados do participante
  Campos:
    ticket - Identificação do participante
    nome - Nome do participante
    email - Email do participante
    empresa - Empresa do participante

# brindes - Tabela de brindes
  Campos:
    brindeId - Identificação do brinde
    descricao - Descrição do brinde

# participantesBrindes - Armazena os nomes dos ganhadores e seus respectivos brindes
  Campos:
    participanteBrindeId - Identificação do sorteio
    ticket - Ticket do participante ganhador
    brindeId - Identificação do brinde sorteado

# participantesExcluidos - Armazena os tickets dos participantes que não devem mais participar dos sorteios
  Campos:
    ticket - Ticket do participante que não será sorteado
```

## Uso

Assim que a aplicação for acessada no navegador, o primeiro prêmio e o botão Sortear serão exibidos.
Clicando no botão sortear, um nome será exibido em uma janela e com isso você pode:

1 - Confirmar o sorteio (neste caso será armazenado o ganhador e o prêmio) e automaticamente o próximo prêmio já será exibido na tela.
2 - Sortear novamente (neste caso um novo nome será exibido e o nome antigo ainda permanecerá na lista, podendo ser sorteado futuramente).
3 - Remover o nome sorteado da listagem (neste caso o nome sorteado será armazenado em uma tabela de eliminados e não mais participará dos sorteios).

## Melhorias

* Criar uma área administrativa para realizar a inclusão/edição/exclusão de brindes;
* Criar uma área administrativa para realizar a inclusão/edição/exclusão/importação dos participantes;
* Criar uma área administrativa para realizar a inclusão/exclusão dos participantes eliminados do sorteio.
