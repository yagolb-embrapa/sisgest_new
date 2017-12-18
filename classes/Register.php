<?php

function pluralize($word) {
    if(false !== strpos($word, '_'))
        $word = pluralize(array_shift(split('_', $word))) . '_' . pluralize(implode('_', array_slice(split('_', $word), 1)));

    $last = substr($word, -1);

    switch($last) {
        case 'a':
        case 'e':
        case 'i':
        case 'u':
        case 'n':
            $word = $word . 's';
            break;

        case 'o':
            if(substr($word, -2, 1) == 'a')
                if(substr($word, -3, 1) == 'r')
                    $word .= 's';
                elseif(substr($word, -3, 1) == 'p')
                    $word = substr($word, 0, -1) . 'es';
                else
                    $word = substr($word, 0, -2) . 'oes';
            else
                $word .= 's';
            $word = (substr($word, -2, 1) == 'a') ? (substr($word, 0, -2) . 'oes') : ($word . 's');
            break;

        case 'l':
            if(substr($word, -2, 1) == 'i')
                if(substr($word, -3, 1) == 'r')
                    $word = substr($word, 0, -1) . 's';
                else
                    $word = substr($word, 0, -2) . 'eis';
            break;

        case 'm':
            $word = substr($word, 0, -1) . 'ns';

        case 'r':
        case 'z':
            $word = $word . 'es';
            break;

        default:
            $word = $word;
    }

    return $word;
}


final class Register extends StdClass {

    private $table_name;
    private $columns_information;
    private $new_register;

    // Tipos de dados que precisam de aspas antes de serem usados em queries
    private static $quoted_fields = array('boolean', 'bool',
                                          'char', 'character', 'character varying', 'varchar', 'text',
                                          'date', 'time', 'time with time zone', 'timestamp', 'interval',
                                          'box', 'line', 'lseg', 'circle', 'path', 'point', 'polygon',
                                          'cidr', 'inet', 'macaddr',
                                          'USER-DEFINED');
 

    // Cria um novo registro e carrega seus campos
    // O metodo e privado para forcar o uso do metodo estatico ::create para criar novos registros
    private function __construct($table, $data = array()) {
        $temp = array_merge(array('data' => array()), $data);

        $this->set_table_name($table);
        $this->load_columns_information();
        $this->new_register = true;


        // Carrega os dados iniciais, caso $temp['data'] esteja definido
        if(sizeof($temp['data']) > 0)
            $this->load_data($temp['data']);
    }



    // Define o nome da tabela
    private function set_table_name($name) {
        $this->table_name = $name;
    }



    // Retorna o nome da tabela
    public function get_table_name() {
        return $this->table_name;
    }



    // Carrega informacoes sobre os campos da tabela
    private function load_columns_information() {
        $query = "SELECT ordinal_position,
                         column_name,
                         data_type,
                         is_nullable::boolean::integer
                  FROM   information_schema.columns
                  WHERE  table_schema = 'public'
                         AND table_name = '{$this->get_table_name()}';";
        $this->columns_information = DB::fetch_all($query);

        // Cria os campos da tabela no objeto corrente
        foreach($this->columns_information as $column) {
            $this->$column['column_name'] = null;
            $this->{'_' . $column['column_name']} = null;
        }
    }



    // Retorna o vetor de informacoes sobre os campos
    public function get_columns_information() {
        return $this->columns_information;
    }



    // Armazena os dados passados ao objeto
    public function load_data($data) {
        if(!is_array($data) || sizeof($data) <= 0)
            return;

        $columns_info = array();

        foreach($this->get_columns_information() as $column) 
            $columns_info[] = $column['column_name'];

        foreach($data as $column => $value)
            if(in_array($column, $columns_info))
                $this->$column = $this->{'_' . $column} = $value;

        $this->new_register = false;
    }



    // Retorna o valor de um campo corretamente formatado
    // Adiciona aspas e escapa alguns caracteres se for necessario
    public function get_column_value($column, $old_value = false) {
        $field_exists = false;
        $nullable = false;
        $type = '';

        // Verifica se o campo existe, se aceita NULL e armazena o tipo do campo
        foreach($this->get_columns_information() as $c) {
            if($column == $c['column_name']) {
                $nullable = $c['is_nullable'];
                $field_exists = true;
                $type = array_shift(explode(' ', $c['data_type']));
                break;
            }
        }

        // Se o campo nao existir, nao retorna nada
        if(!$field_exists)
            return null;

        $quoted = in_array($type, Register::$quoted_fields);
        $value = ($old_value) ? ($this->{'_' . $column}) : $this->$column;

        // Formata corretamente o campo e retorna seu valor
        if($nullable)
            return (empty($value)) ? 'NULL' : ($quoted ? "'{$value}'" : $value);
        else
            return (empty($value)) ? ($quoted ? "''" : 0) : ($quoted ? "'{$value}'" : $value);
            
    }



    // Cria um novo registro
    public static function create($table) {
        // Coleta os campos da tabela
        $query = "SELECT table_name
                  FROM   information_schema.tables
                  WHERE  table_name = '{$table}'
                         AND table_schema = 'public';";
        $rows = DB::fetch_all($query);

        // Se a tabela nao tiver campos, nao retorna nada
        if(sizeof($rows) !== 1)
            return null;

        // Retorna um novo registro de tabela
        return new Register($table);
    }



    // Filtra registros
    // Se encontrar apenas um registro, retorna o objeto do registro
    // Se encontrar muitos registros, retorna um vetor com os objetos
    public static function filter($table, $extra = array()) {
        $reg = Register::create($table);

        $extra = array_merge(array('conditions' => 'all',
                                   'order'      => array()), $extra);

        $cond = $extra['conditions'] ? $extra['conditions'] : ($extra['condition'] ? $extra['condition'] : array());
        $order = $extra['order'];

        // Armazena condicoes de filtragem (dados da clausula WHERE)
        $filter = array();

        if($cond != 'all' && is_array($cond))
            foreach($cond as $field => $value)
                foreach($reg->get_columns_information() as $column)
                    if($column['column_name'] == $field)
                        if(in_array($column['data_type'], Register::$quoted_fields))
                            $filter[] = $field . " = '" . addslashes($value) . "'";
                        else
                            $filter[] = $field . " = " . $value;

        // Armazena dados para ordenacao (clausula ORDER BY)
        $ord = array();

        if(is_array($order))
            foreach($order as $field => $type)
                $ord[] = "{$field} {$type}";
        elseif(is_array($level))
            foreach($order as $field => $type)
                $ord[] = "{$field} {$type}";


        // Monta a query para retornar os dados filtrados
        $query = "SELECT   *
                  FROM     {$reg->get_table_name()} " . 
                  (sizeof($filter) > 0 ? (" WHERE " . implode(' AND ', $filter)) : '') .
                  (sizeof($ord) > 0 ? (" ORDER BY " . implode(', ', $ord)): '') . ";";
        $rows = DB::fetch_all($query);

        // Se nenhum registro for encontrado, nao retorna nada
        if(sizeof($rows) == 0) {
            return null;

        // Se encontrar apenas um registro, cria um objeto e o retorna
        } else if(sizeof($rows) == 1) {
            unset($reg);
            $reg = new Register($table, array('data'  => $rows[0],
                                              'order' => $order));

            return $reg;

        // Se encontrar varios registros, cria um vetor de objetos e o retorna
        } else {
            $registers = array();

            foreach($rows as $row)
                $registers[] = new Register($table, array('data' => $row,
                                                          'order' => $order));

            return $registers;
        }
    }



    // Salva o registro, verificando se ele deve ser inserido ou atualizado
    public function save() {
        $query = "";

        // Se for um novo registro, cria uma query INSERT INTO
        if($this->new_register) {
            $columns = array();
            $values = array();

            foreach($this->get_columns_information() as $column) {
                if(('id' == $column['column_name'] && $this->get_column_value($column['column_name']) > 0) || 'id' != $column['column_name']) {
                    $columns[] = $column['column_name'];
                    $values[] = $this->get_column_value($column['column_name']);
                }
            }

            $query = "INSERT INTO {$this->get_table_name()}(" . implode(', ', $columns) . ") VALUES(" . implode(', ', $values) . ");";

            unset($columns, $values);

        // Se o registro ja existe e foi alterado, cria uma query UPDATE
        } else {
            $sets = array();
            $conds = array();

            $changed = false;

            // Cria o trecho da query com os valores para SET e as condicoes para WHERE
            foreach($this->get_columns_information() as $column) {
                $value = $this->get_column_value($column['column_name']);
                $old_value = $this->get_column_value($column['column_name'], true);

                // Verifica se algum campo foi modificado
                if($value !== $old_value)
                    $changed = true;

                $sets[] = "{$column['column_name']} = {$value}";
                $conds[] = ($old_value != 'NULL') ? ("{$column['column_name']} = {$old_value}")
                                                  : ("({$column['column_name']} IS NULL OR {$column['column_name']} = '')");
            }

            // Cria query de atualizacao apenas se houve alguma modificacao
            if($changed)
                $query = "UPDATE {$this->get_table_name()}
                          SET    " . implode(', ', $sets) . "
                          WHERE  " . implode(' AND ', $conds) . ";";

            unset($sets, $conds);
        }

        // Tenta salvar o registro
        // Se salvar corretamente, atualiza os dados do objeto para que da proxima vez ele seja atualizado, caso seja um registro novo
        if(DB::execute($query)) {
            $this->new_register = false;
            $data = array();

            foreach($this->get_columns_information() as $column) {
                $this->{'_' . $column['column_name']} = $this->$column['column_name'];
                $data[$column['column_name']] = $this->$column['column_name'];
            }

            return true;
        }

        return false;
    }



    // Exclui um registro
    public function delete() {
        $conds = array();

        // Deleta o registro apenas se ele nao for um registro novo
        if($this->new_register)
            return false;

        // Cria as condicoes para o registro ser excluido
        foreach($this->get_columns_information() as $column) {
            $value = $this->get_column_value($column['column_name'], true);
            $conds[] = "{$column['column_name']} = {$value}"; 
        }

        // Cria query para excluir o registro
        $query = "DELETE
                  FROM   {$this->get_table_name()}
                  WHERE  " . implode(' AND ', $conds) . ";"; 
        $this->new_register = true;

        return DB::execute($query);
    }

}

?>
