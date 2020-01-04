# ErrorHandler

## Inclusão da Classe

```php
include_once "diretorio/ErrorHandler.class.php";

```

## Chamada da Classe

```php
$dir = new ErrorHandler();
```

### Como gerar um Erro

Essa classe utiliza da mesma funcionalidade do bloco **try{}catch(){}** inserindo apenas algumas utilidades, por isso a forma de adicionar um erro à classe é a mesma.

```php
$ErrorHandler->try(function(){
    $numero = 2;
    if($numero == 1){
        // Código SUCESSO
    } else {
        // Primeiro parâmetro é a mensagem de erro (String)
        // Segundo parâmetro é o código do erro (Interito)
        throw new Exception("Erro Personalizado.", 1);
    }
})->catch(function($e){
    // Tratamento do Erro.
});
```

### Tratando os Erros

Você deverá separar o código que receberá o tratamento dentro de uma função e posteriormente chama-la para tratamento, pode-se feito essa chamada de 2 formas:

> Forma 1: Definindo uma função externa em casos que necessite passar um retorno para outra função ou parte do código.

```php
$testeDeError1 = function () {
    // Bloco de Código a ser testado
};

// O parâmetro $e é obrigatório para o catch
$tratamentoErro = function($e) {
    // Tratamento do Erro
}

$ErrorHandler->try($testeDeError1)->catch($tratamentoErro);
```

O código acima apenas exemplifica a forma 1 de como passar o bloco de código ao tratamento.

> Forma 2: Definindo uma função interna

```php
$ErrorHandler->try(function(){
    // Bloco de Código a ser testado
})->catch(function($e){
    // Tratamento do Erro
});
```

Diferentemente dos blocos **try{}catch(){}** convencionais essa classe possibilita o teste de erros em bocos separadamente em uma mesma chamada, usando **try{}catch(){}** ficaria assim:

```php
try {
    // Bloco 1 a ser testado
} catch(exception $e) {
    // Tratamento do Erro
}

try {
    // Bloco 2 a ser testado
} catch(exception $e) {
    // Tratamento do Erro
}
```

As vezes precisamos tratar um erro em 2 partes importantes do código gerando outro bloco **try{}catch(){}** já que não podemos inseri-lo no primeiro bloco, pois a execução do código irá parar assim que um erro surgir. Nesta classe podemos evitar esse segundo bloco da seguinte forma:

```php
$ErrorHandler->try(function(){
    // Bloco 1 de Código a ser testado
})->try(function(){
    // Bloco 2 de Código a ser testado
})->catch(function($e){
    // Tratamento do Erro
});

// Caso prefira manter os 2 catch:

$ErrorHandler->try(function(){
    // Bloco 1 de Código a ser testado
})->catch(function($e){
    // Tratamento do Erro bloco 1
})->try(function(){
    // Bloco 2 de Código a ser testado
})->catch(function($e){
    // Tratamento do Erro bloco 2
});
```
O erro gerado pelo bloco 1 será passado na forma de **Array** para o **catch** tendo ele a segunte estrutura:

```php
Array
(
    [0] => Array
        (
            [code] => "Código do Erro"
            [menssage] => "Mensagem do Erro"
            [file] => "Arquivo que gerou o erro"
            [line] => "Linha do Erro"
            [previous] => ""
            [trace] => "Array Trace do Erro"
            [traceAsString] => "String Trace do Erro"
        )
)

// Você poderá mostrar a mensagem do erro no catch da segunte forma:

$ErrorHandler->try(function(){
    // Bloco de Código a ser testado
})->catch(function($e){
    echo $e[0]["menssage"];
});
```

Cada bloco de **try** criado irá gerar um indice do erro no array retornado a **$e** do catch (se o erro existir). Seguindo nosso exemplo de usar 2 blocos **try{}catch(){}** vamos obter resultados diferentes dependendo do comportamento do código a ser testado juntamente com a organização escolhida para os blocos **try{}catch(){}** ex:

```php

$ErrorHandler->try()->try()->catch();

// A estrutura acima irá gerar esse array na $e do catch caso os 2 blocos tenham erros

Array
(
    [0] => Array
        (
            [code] => "Código do Erro"
            [menssage] => "Mensagem do Erro"
            [file] => "Arquivo que gerou o erro"
            [line] => "Linha do Erro"
            [previous] => ""
            [trace] => "Array Trace do Erro"
            [traceAsString] => "String Trace do Erro"
        )
    [1] => Array
        (
            [code] => "Código do Erro"
            [menssage] => "Mensagem do Erro"
            [file] => "Arquivo que gerou o erro"
            [line] => "Linha do Erro"
            [previous] => ""
            [trace] => "Array Trace do Erro"
            [traceAsString] => "String Trace do Erro"
        )
)

$ErrorHandler->try()->catch()->try()->catch();

/**
 * Já nessa forma o primeiro catch receberá o Array em $e com apenas 1
 * indice, já o segundo catch receberá o Array em $e com 2 indices
 * (como foi mostrado acima), lembrando que estamos supondo que os 2
 * blocos try retornaram erros.
 * 
 * Uma observação importante é que o código do bloco 1 e 2 serão
 * interrompidos assim que o erro surge, assim como o try{}catch(){}
 * faz, interrompendo o restante do código subsequente dentro do 
 * bloco.
 */
```

OBS: Utilizando 2 ou mais blocos de **try{}catch(){}** será praticamente impossivel saber de qual bloco veio o erro se utilizar mensagens padões para teste, pode-se evitar confusão informando um código do erro no bloco ***throw new Exception*** assim você consegue usar mensagens de erros iguais conseguindo saber de onde veio cada uma delas.

Aproveitem!!!
