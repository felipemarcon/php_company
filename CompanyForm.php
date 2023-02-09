<?php

    require_once __DIR__ . '/classes/Company.php';

class CompanyForm
{
    private $html;
    private $data;

    public function __construct()
    {
        $this->html = file_get_contents('html/form.html');
        $this->data = [
            'id' => null,
            'cnpj' => null,
            'cep' => null,
            'complement' => null,
            'number' => null,
            'fantasy' => null,
            'name' => null,
            'address' => null,
            'district' => null,
            'phone' => null,
            'mail' => null,
            'city' => null,
            'state' => null
        ];
    }

    public function edit($param)
    {
        try {
            $id = (int)$param['id'];
            $company = Company::find($id);
            $this->data = $company;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function save($param)
    {
        try {
            Company::save($param);
            $this->data = $param;
            print "<div class='trigger trigger-sucess center'><p>Pessoa salva com Sucesso!</p></div>";
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function show()
    {
        $this->html = str_replace(
            ['{id}','{cnpj}', '{name}', '{fantasy}', '{cep}', '{address}', '{district}', '{complement}', '{number}', '{phone}', '{mail}', '{city}', '{state}'],
            [
                $this->data['id'],
                $this->data['cnpj'],
                $this->data['name'],
                $this->data['fantasy'],
                $this->data['cep'],
                $this->data['address'],
                $this->data['district'],
                $this->data['complement'],
                $this->data['number'],
                $this->data['phone'],
                $this->data['mail'],
                $this->data['city'],
                $this->data['state'],
                $this->data['cnpj'],
                $this->data['fantasy'],
                $this->data['complement'],
                $this->data['number']
            ],
            $this->html
        );

        //$this->html = str_replace($search, $this->data, $this->html);

        print  $this->html;
    }
}
