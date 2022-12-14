{
    "__comment":"雛形用モデル",
    "title__comment":"クラス用タイトル表記",
    "title":"雛形データ用ライブラリー",
    "description__comment":"クラス用説明文表記",
    "description":"雛形データの取得および処理する為の関数群",
    "constOnly__comment":"定数宣言のみ title=日本語表記, key=定数名, val=値",
    "constOnly":[
        {
            "title":"ログイン対象",
            "key":"LOGIN_KEY",
            "val":"Base_lib::ADMIN_DIR"
        }
    ],
    "construct__comment":"コントラクト description=説明, data=内容(配列)",
    "construct":[
        {
            "description":"一覧テンプレート情報を取得",
            "data":[
                "$this->load->library('login_lib', array('key' => self::LOGIN_KEY));"
            ]
        }
    ],
    "sharedTemplate__comment":"共通テンプレート description=説明, data=内容(配列)",
    "sharedTemplate":[
        {
            "description":"クラス定数をセット",
            "data":[
                "$returnVal['const'] = $this->GetBaseConstList();"
            ]
        },
        {
            "description":"ログ出力",
            "data":[
                "Base_lib::ConsoleLog($returnVal);",
                "Base_lib::ConsoleLog($_SERVER);",
                "Base_lib::ConsoleLog($_SESSION);",
                "Base_lib::ConsoleLog(validation_errors());"
            ]
        }
    ],
    "templateList__comment":"各テンプレート情報取得用関数",
    "templateList":[
        {
            "description__comment":"関数説明文",
            "description":"一覧テンプレート情報を取得",
            "key__comment":"関数名",
            "key":"ListTemplate",
            "arg__comment":"関数引数",
            "arg":[
                {
                    "title":"ID",
                    "key":"$id",
                    "type":"string",
                    "column":"id",
                    "default":"''"
                }
            ],
            "return":"$this->sharedTemplate($returnVal)",
            "returnType__comment":"返値の型",
            "returnType":"array",
            "iniSet":[
                {
                    "description":"読み込み時間を延長",
                    "data":[
                        "ini_set('max_execution_time', '90');"
                    ]
                }
            ],
            "selectList":{
                "title":"選択情報をセット",
                "list":{
                    "count":"$this->pagenavi_lib->GetListCount()"
                }
            },
            "library":[
                "$this->load->library('pagenavi_lib')"
            ],
            "var":[
                {
                    "description":"WHERE情報をセット",
                    "key":"$whereSql",
                    "val":"array();"
                }
            ],
            "formList":"$this->FormDefaultList()",
            "list":{
                "whereSql":[
                    {
                        "title":"キーワード",
                        "if":"$returnVal['form']['search_keyword'] != ''",
                        "list":[
                            "Example_lib::MASTER_TABLE . " . name LIKE '%" . Base_lib::AddSlashes($returnVal['form']['search_keyword']) . "%'";"
                        ]
                    }
                ],
                "page":{
                    "count":"$this->GetListCount($whereSql)",
                    "pager":"$this->pagenavi_lib->GetValeus($returnVal['count'], $returnVal['form']['page'], $returnVal['form']['select_count'])",
                    "limit":{
                        "begin":"($returnVal['pager']['listStart'] - 1)",
                        "row":"$returnVal['form']['select_count']"
                    }
                },
                "order":[
                    {
                        "key":"User_lib::MASTER_TABLE . ' . edit_date'",
                        "arrow":"DESC"
                    }
                ],
                "getList":"$this->GetList($whereSql, $orderSql, $limitSql)"
            },
            "otherList__comment":"最後に書き出し、全てそのまま書出す",
            "otherList":[
                ""
            ]
        }
    ],
    "formList__comment":"フォーム用配列 description=関数説明文, key=関数名, list=配列",
    "formList":[
        {
            "description":"一覧フォーム用配列",
            "key":"FormDefaultList",
            "list":[
                "page",
                "select_count",
                "search_keyword"
            ]
        },
        {
            "description":"入力フォーム用配列",
            "key":"FormInputList",
            "list":[
                "id",
                "name"
            ]
        }
    ],
    "validList__comment":"エラーチェック用配列 description=関数説明文, key=関数名, list=配列",
    "validList":[
        {
            "description":"入力ページ エラーチェック配列",
            "key":"ConfigInputValues",
            "list":[
                {
                    "field":"id",
                    "label":"ID",
                    "rules":"required"
                },
                {
                    "field":"name",
                    "label":"名前",
                    "rules":"required"
                }
            ]
        }
    ]
}
