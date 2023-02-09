<?php

    require_once __DIR__ . '/classes/Company.php';

class CompanyList
{
    private $html;

    public function __construct()
    {
        $this->html = file_get_contents('html/list.html');
    }

    public function delete($param)
    {
        try {
            $id = (int)$param['id'];
            Company::delete($id);
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function load()
    {
        try {
            $rows = '';
            foreach (Company::all() as $company) {
                $row = file_get_contents('html/row.html');

                $row = str_replace(
                    ['{id}', '{company_name}', '{company_city}', '{company_state}','{company_phone}','{company_mail}'],
                    [
                        $company['id'],
                        $company['name'],
                        $company['city'],
                        $company['state'],
                        $company['phone'],
                        $company['mail']
                    ],
                    $row
                );

                $rows .= $row;
            }
            $this->html = str_replace(
                '{rows}',
                $rows,
                $this->html
            );
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    public function show()
    {
        $this->load();
        print $this->html;
    }
}
