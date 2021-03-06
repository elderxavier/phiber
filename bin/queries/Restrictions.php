<?php
/**
 * Copyright (c) 2017. Este código foi feito por @marciioluucas, sob licença MIT
 */

namespace phiber\bin\queries;



/**
 * Classe responsável por fazer as restrições das operações do banco
 * @package bin
 */
class Restrictions
{
    /**
     * @var array
     */
    private static $fieldsAndValues = [];

    /**
     * Restrictions constructor.
     */
    public function __construct()
    {
        self::$fieldsAndValues = [];
    }

    /**
     * @return array
     */
    public function getFieldsAndValues(): array
    {
        return self::$fieldsAndValues;
    }



    /**
     * Faz a query de comparação IGUAL
     * Exemplo:
     *  equals("idade",15);
     *  Criará um pedaço da query do banco assim -> idade = :condition_idade
     *  OBS: O ":condition_idade" é o responsável por depois fazer o binding do valor para
     *  evitar SQL Injection.
     * @param $param1
     * @param $param2
     * @return array
     */
    public function equals($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        return
            [
                "where" =>$param1 . " = :condition_" . $param1
            ];

    }

    /**
     * Faz a query de comparação MAIOR QUE
     * Exemplo:
     *  biggerThen("idade",15);
     *  Criará um pedaço da query do banco assim -> idade > :condition_idade
     *  OBS: O ":condition_idade" é o responsável por depois fazer o binding do valor para
     *  evitar SQL Injection.
     * @param $param1
     * @param $param2
     * @return array
     */
    public function biggerThen($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        return [
            "where" => $param1 . " > :condition_" . $param1
        ];
    }

    /**
     * Faz a query de comparação MAIOR OU IGUAL A
     * Exemplo:
     *  greaterThan("idade",15);
     *  Criará um pedaço da query do banco assim -> idade >= :condition_idade
     *  OBS: O ":condition_idade" é o responsável por depois fazer o binding do valor para
     *  evitar SQL Injection.
     * @param $param1
     * @param $param2
     * @return array
     */
    public function greaterThan($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        return [
            "where" => $param1 . " >= :condition_" . $param1,
        ];
    }

    /**
     * Faz a query de comparação MENOR QUE
     * Exemplo:
     *  lessThen("idade",15);
     *  Criará um pedaço da query do banco assim -> idade < :condition_idade
     *  OBS: O ":condition_idade" é o responsável por depois fazer o binding do valor para
     *  evitar SQL Injection.
     * @param $param1
     * @param $param2
     * @return array
     */
    public function lessThen($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        return [
            "where" => $param1 . " < :condition_" . $param1,
        ];
    }

    /**
     * Faz a query de comparação MENOR OU IGUAL A
     * Exemplo:
     *  lessLike("idade",15);
     *  Criará um pedaço da query do banco assim -> idade <= :condition_idade
     *  OBS: O ":condition_idade" é o responsável por depois fazer o binding do valor para
     *  evitar SQL Injection.
     * @param $param1
     * @param $param2
     * @return array
     */
    public function lessLike($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);
        return [
            "where" => $param1 . " <= :condition_" . $param1,
        ];
    }

    /**
     * Faz a query de comparação LIKE
     * Exemplo:
     *  like("nome","Jhon Snow");
     *  Criará um pedaço da query do banco assim -> idade LIKE %:condition_nome%
     *  OBS: O ":condition_nome" é o responsável por depois fazer o binding do valor para
     *  evitar SQL Injection.
     * @param $param1
     * @param $param2
     * @return array
     */
    public function like($param1, $param2)
    {
        self::addFieldsAndValues($param1, $param2);

        return [
            "where" => $param1 . " LIKE CONCAT('%',:condition_" . $param1 . ",'%')",
        ];
    }

    /**
     * Faz a query de conjunção OU
     * Exemplo:
     *  $condicao1 = equals("idade",15);
     *  $condicao2 = like("nome","Jhon");
     *  either($condicao1,$condicao2);
     *
     *  Criará um pedaço da query do banco assim ->
     *    (idade = :condition_idade or nome like %:condition_nome%);
     *  OBS: O ":condition_idade, :condition_nome" são responsáveis por depois fazer o
     * binding do valor para evitar SQL Injection.
     * @param $condition1
     * @param $condition2
     * @return array
     */
    public function either($condition1, $condition2)
    {

        return [
            "where" => "(" . $condition1['where'] . " OR " . $condition2['where'] . ")",
        ];
    }

    /**
     * Faz a query de conjunção E
     * Exemplo:
     *  $condicao1 = eq("idade",15);
     *  $condicao2 = like("nome","Jhon");
     *  or($condicao1,$condicao2);
     *
     *  Criará um pedaço da query do banco assim ->
     *    (idade = :condition_idade and nome like %:condition_nome%);
     *  OBS: O ":condition_idade, :condition_nome" são responsáveis por depois fazer o
     * binding do valor para evitar SQL Injection.
     * @param $condition1
     * @param $condition2
     * @return array
     */
    public function and ($condition1, $condition2)
    {

        return [
            "where" => "(" . $condition1['where'] . " AND " . $condition2['where'] . ")"
        ];
    }

    /**
     * Função para determinar os campos que quer buscar no SELECT
     * Exemplo: fields("nome, id");
     * Gerará: Select nome, id from ...
     * Caso não informar campos, retornará todos.
     * @param $fields
     * @return array
     */
    public function fields($fields)
    {
        if(!empty($fields)){
            return ["fields" => $fields];
        }
        return ["fields"=>["*"]];
    }

    /**
     * Adiciona os campos e os valores.
     * @ignore
     * @param $field
     * @param $value
     */
    private function addFieldsAndValues($field, $value)
    {
        self::$fieldsAndValues['fields_and_values'][$field] = $value;
    }




}