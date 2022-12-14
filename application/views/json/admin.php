{
    "__comment":"管理画面生成用JSON",
    "table__comment":"selectDisable:一部未利用（allは全テーブル共通）, disable:テーブル自体利用しない",
    "table":{
        "selectDisable":{
            "m_admin":[
                "company_id",
                "authority"
            ],
            "all":[
                "id",
                "sort_id",
                "regist_date",
                "edit_date"
            ]
        },
        "disable":[
            "m_pref_temp"
        ]
    },
    "login__comment":"table:ログイン対象テーブル, id:ID対象カラム, password:パスワード対象カラム first_page:ログイン後の最初のページ",
    "login":{
        "table":"m_admin",
        "id":"account",
        "id_name":"アカウント",
        "password":"password",
        "password_name":"パスワード",
        "first_page":"top"
    }
}
