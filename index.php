<?php
/*
Plugin Name: Plugin B2Cor - AgenciaLINK.com
Plugin URI:  https://suporte.agencialink.com.br/b2cor/plugin-wp-leads
Description: Formulário customizável, que captura leads integrado ao B2Cor CRM da agencialink. Utilizando este formulário em suas páginas os leads capturados serão enviados automaticamente para o B2Cor CRM e Funil de Vendas facilitando muito seu trabalho e agilidade.
Version: 1.6.5
Author: agencialink.com
Author URI: https://agencialink.com/
*/

// REGISTRA O MENU

add_action('admin_menu', 'menuAgenciaLink');

function menuAgenciaLink()
{
    add_menu_page(
        'Plugin B2Cor - AgenciaLINK.com',
        'Plugin B2Cor - AgenciaLINK.com',
        'manage_options',
        'agencialink-b2cor-leads',
        'agenciaLinkOptions',
        'dashicons-welcome-learn-more'
    );
    add_action('admin_init', 'registerAgenciaLinkOptions');
}

// CRIA AS OPÇÕES NO BANCO DE DADOS

function registerAgenciaLinkOptions()
{
    register_setting('agencia_link_options', 'alink_b2cor_key');
}

// LINK DIRETO PARA A PAGINA DE OPÇÕES

function linkOptionsAgenciaLink($links)
{
    $settings_link = '<a href="/wp-admin/admin.php?page=agencialink-b2cor-leads">Configurações</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'linkOptionsAgenciaLink');


// RESGITRA O SHORTCODE DO FORMULÁRIO

function shortcodeAgenciaLink($attr)
{
    $attr = shortcode_atts(
        [
            'campo-telefone' => 'sim',
            'campo-mensagem' => 'sim',

            'cor-fundo-campos' => 'ffffff',
            'cor-borda-campos' => 'DCDFE6',
            'cor-fonte-campos' => '333333',

            'cor-label' => '606266',
            'cor-alertas' => 'F56C6C',

            'texto-botao' => 'Enviar',
            'cor-fundo-botao' => '007bff',
            'cor-fonte-botao' => 'ffffff',

            'msg-sucesso' => 'Sua mensagem foi enviada com sucesso! Em breve um de nossos consultores entrará em contato.',
            'cor-msg-sucesso' => '007bff',
            'fonte-msg-sucesso' => '14px',

            'espaco-entre-campos' => '22px',
            'largura-form' => '600px',
            'layout-form' => 'duas-colunas',

            'altura-iframe-desktop' => '',
            'altura-iframe-mobile' => '',
        ],
        $attr,
        'alink-form-leads'
    );

    ob_start();

    $b2cor_key = get_option('alink_b2cor_key');

    if ($b2cor_key) {

        $telefone = str_replace(
            array(' ', '&', '&', 'á', 'ã', 'à', 'é', 'ê', 'í', 'ç', 'õ', 'ô', 'ó', 'ú', '-', "'"),
            array('', '', '', 'a', 'a', 'a', 'e', 'e', 'i', 'c', 'o', 'o', 'o', 'u', '', ''),
            $attr['campo-telefone']
        );

        $mensagem = str_replace(
            array(' ', '&', '&', 'á', 'ã', 'à', 'é', 'ê', 'í', 'ç', 'õ', 'ô', 'ó', 'ú', '-', "'"),
            array('', '', '', 'a', 'a', 'a', 'e', 'e', 'i', 'c', 'o', 'o', 'o', 'u', '', ''),
            $attr['campo-mensagem']
        );

        $link .= "https://formulario.agencialink.com.br/?"; // link do formulário
        $link .= "b2cor_key=$b2cor_key&";
        $link .= "telefone=" . $telefone . "&";
        $link .= "mensagem=" . $mensagem . "&";

        $link .= "cor-fundo-campos=" . str_replace("#", '', $attr['cor-fundo-campos']) . "&";
        $link .= "cor-borda-campos=" . str_replace("#", '', $attr['cor-borda-campos']) . "&";
        $link .= "cor-fonte-campos=" . str_replace("#", '', $attr['cor-fonte-campos']) . "&";

        $link .= "cor-label=" . str_replace("#", '', $attr['cor-label']) . "&";
        $link .= "cor-alertas=" . str_replace("#", '', $attr['cor-alertas']) . "&";

        $link .= "texto-botao=" . $attr['texto-botao'] . "&";
        $link .= "cor-fundo-botao=" . str_replace("#", '', $attr['cor-fundo-botao']) . "&";
        $link .= "cor-fonte-botao=" . str_replace("#", '', $attr['cor-fonte-botao']) . "&";

        $link .= "msg-sucesso=" . $attr['msg-sucesso'] . "&";
        $link .= "cor-msg-sucesso=" . str_replace("#", '', $attr['cor-msg-sucesso']) . "&";
        $link .= "fonte-msg-sucesso=" . $attr['fonte-msg-sucesso'] . "&";

        $link .= "espaco-entre-campos=" . $attr['espaco-entre-campos'] . "&";
        $link .= "largura-form=" . $attr['largura-form'] . "&";
        $link .= "layout-form=" . $attr['layout-form'] . "&";

        $link .= "altura-iframe-desktop=" . $attr['altura-iframe-desktop'] . "&";
        $link .= "altura-iframe-mobile=" . $attr['altura-iframe-mobile'] . "&";

        $link .= "origem=" . $_SERVER['SERVER_NAME'];

        $class = "all-fields";
        $desktop_height = '500px';
        $mobile_height = '500px';

        if ($attr['layout-form'] == "duas-colunas") {

            if ($telefone == "sim" && $mensagem  == "sim") {
                $class = "all-fields";
                $desktop_height = '500px';
                $mobile_height = '500px';
            }

            if ($telefone == "nao" && $mensagem  == "nao") {
                $class = "no-fields";
                $desktop_height = '292px';
                $mobile_height = '292px';
            }

            if ($telefone == "nao" && $mensagem == "sim") {
                $class = "have-msg";
                $desktop_height = '327px';
                $mobile_height = '410px';
            }

            if ($telefone == "sim" && $mensagem  == "nao") {
                $class = "have-phone";
                $desktop_height = '210px';
                $mobile_height = '370px';
            }
        } else {

            if ($telefone == "sim" && $mensagem  == "sim") {
                $class = "all-fields";
                $desktop_height = '540px';
                $mobile_height = '540px';
            }

            if ($telefone == "nao" && $mensagem  == "nao") {
                $class = "no-fields";
                $desktop_height = '322px';
                $mobile_height = '322px';
            }

            if ($telefone == "nao" && $mensagem == "sim") {
                $class = "have-msg";
                $desktop_height = '450px';
                $mobile_height = '450px';
            }

            if ($telefone == "sim" && $mensagem  == "nao") {
                $class = "have-phone";
                $desktop_height = '400px';
                $mobile_height = '400px';
            }
        }

        if ($attr['altura-iframe-desktop']) {
            $desktop_height = $attr['altura-iframe-desktop'];
        }

        if ($attr['altura-iframe-mobile']) {
            $mobile_height =  $attr['altura-iframe-mobile'];
        }
?>

        <iframe id="agencialink-window" class="<?php echo $class; ?>" src="<?php echo $link; ?>"></iframe>

        <style>
            #agencialink-window.<?php echo $class; ?> {
                width: 100%;
                overflow: hidden !important;
                height: <?php echo $desktop_height; ?>;
                border: 0;
                background: transparent;
            }

            @media only screen and (min-device-width : 0px) and (max-width : 874px) {
                #agencialink-window.<?php echo $class; ?> {
                    height: <?php echo $mobile_height; ?>;
                }
            }
        </style>

    <?php } else { ?>

        <?php if (is_user_logged_in()) { ?>

            <div class="alink-message">
                A chave B2cor deve ser inserida na <a href='/wp-admin/admin.php?page=agencialink-b2cor-leads' target="_blank">página de opções</a>
                da agencialink para gerar o formulário de captação de Leads.
            </div>

            <style>
                .alink-message {
                    text-align: center;
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    padding: 20px;
                    border-radius: 9px;
                    font-size: 14px;
                    color: #606266;
                    clear: both;
                }

                .alink-message a {
                    color: #007bff;
                    font-size: 14px;
                }
            </style>

        <?php } ?>

    <?php
    }
    return ob_get_clean();
}

add_shortcode('alink-form-leads', 'shortcodeAgenciaLink');

// GERA A PÁGINA DE OPÇÕES

function agenciaLinkOptions()
{ ?>

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <!-- VUE -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <!-- ELEMENT -->
    <link rel="stylesheet" href="<?php echo plugins_url('agencialink-b2cor-leads/assets/css/admin/element.css', dirname(__FILE__)); ?>">
    <script src="<?php echo plugins_url('agencialink-b2cor-leads/assets/js/element.js', dirname(__FILE__)); ?>"></script>
    <script src="<?php echo plugins_url('agencialink-b2cor-leads/assets/js/pt-br.js', dirname(__FILE__)); ?>"></script>

    <div id="agencia-link">
        <el-row :gutter="0" align="middle">
            <el-col class="alink-container" :xs="24" :sm="24" :md="12" :span="12">
                <form method="post" action="options.php">
                    <div class="alink-head">
                        <el-row :gutter="20">
                            <el-col class="logo" :xs="12" :span="10">
                                <a href="https://agencialink.com" target="_blank">
                                    <img src="<?php echo plugins_url('agencialink-b2cor-leads/assets/images/admin/logo.png', dirname(__FILE__)); ?>" alt="Agencialink" />
                                </a>
                            </el-col>
                            <el-col class="logo-b2cor" :xs="24" :span="8">
                                <a href="https://b2cor.com" target="_blank">
                                    <img src="<?php echo plugins_url('agencialink-b2cor-leads/assets/images/admin/b2cor.png', dirname(__FILE__)); ?>" style="width: 159px;" alt="B2Cor" />
                                </a>
                            </el-col>
                            <el-col class="support" :xs="12" :span="6">
                                <el-button @click="openSupport" type="primary" style="font-size: 16px"><i class="wp-menu-image dashicons-before dashicons-welcome-learn-more"></i> Suporte</el-button>
                            </el-col>

                        </el-row>
                    </div>
                    <div class="alink-body">

                        <?php settings_fields('agencia_link_options'); ?>
                        <?php do_settings_sections('agencia_link_options'); ?>

                        <div class="welcome">
                            <p>Olá! Seja bem vindo à agencialink!</p>
                            <p class="txt2">Nesta página você poderá configurar a chave b2cor do nosso formulário de captação de Leads.</p>
                        </div>

                        <h1>Plugin WP B2Cor Leads <font> (v1.6.5) </font>
                        </h1>

                        <el-tabs type="card" v-model="activeName" @tab-click="handleClick">
                            <el-tab-pane label="Início" name="inicio">

                                <div class="field b2-cor">
                                    <label>
                                        B2cor Key (obrigatório)
                                    </label>
                                    <div class="infos">O B2cor disponibiliza uma API Javascript para o cadastro de indicações no CRM. Insira a chave no campo abaixo:</div>
                                    <div>
                                        <input type="text" name="alink_b2cor_key" value="<?php echo esc_attr(get_option('alink_b2cor_key')); ?>" placeholder="Ex: ab93adaa54974eXXXXXXXXXXXXXXXXXX" />
                                    </div>
                                </div>

                                <div class="field shortcode">
                                    <label>
                                        Shortcode do formulário de Leads
                                    </label>
                                    <div class="infos">Copie o shortcode abaixo e cole na página onde deseja exibir o formulário:</div>
                                    <div class="copy">
                                        <input type="text" readonly value='[alink-form-leads]' />
                                    </div>
                                    <br>
                                    Na aba <b>"Parâmetros do Shortcode"</b> acima estão todos os atributos que podem ser configurados no shortcode.
                                </div>
                            </el-tab-pane>
                            <el-tab-pane label="Parâmetros do Shortcode" name="parametros">
                                <div class="field shortcode">
                                    <label style="margin-bottom: 17px">
                                        Parâmetros do shortcode
                                    </label>
                                    <el-table border :data="tableData" max-height="340" style="width: 100%" :fit="true">
                                        <el-table-column label="Parâmetro" width="145">
                                            <template slot-scope="scope">
                                                <input class="param" type="text" readonly :value='scope.row.parametro' />
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="descricao" label="Descrição" width="250">
                                        </el-table-column>
                                        <el-table-column label="Valores possíveis">
                                            <template slot-scope="scope">
                                                <div v-html="scope.row.values"> </div>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Padrão" width="145">
                                            <template slot-scope="scope">
                                                <b>
                                                    <div v-if="scope.row.padrao == '#ffffff'" :style="`color: #666`" v-html="scope.row.padrao"></div>
                                                    <div v-else :style="`color: ${scope.row.padrao}`" v-html="scope.row.padrao"></div>
                                                </b>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                    <label style="margin: 25px 0 17px">
                                        Como aplicar parâmetros no shortcode?
                                    </label>
                                    <div class="infos">Veja no exemplo abaixo como utilizar os parâmetros, você pode editar o shortcode e copiar se preferir: </div>
                                    <div class="ex-shortcode">
                                        <textarea>  [alink-form-leads texto-botao="Enviar mensagem" cor-fundo-botao="#007bff" msg-sucesso="Sucesso!"]  </textarea>
                                    </div>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                    </div>

                    <div class="alink-footer">
                        <el-row :gutter="20">
                            <el-col class="btn" :xs="24" :span="24">
                                <el-button native-type="submit" @click="updateOptions" type="primary">Salvar alterações</el-button>
                            </el-col>
                        </el-row>
                    </div>

                </form>
            </el-col>
        </el-row>
    </div>

    <script>
        // ELEMENT PT-BR
        ELEMENT.locale(ELEMENT.lang.ptBr);
        new Vue({
            el: '#agencia-link',
            data() {
                return {
                    activeName: 'inicio',
                    tableData: [{
                        parametro: 'campo-telefone',
                        descricao: 'Controla a exibição do campo "Telefone Fixo" no formulário.',
                        values: '<b>sim</b> ou <b>nao</b>',
                        padrao: '<b>sim</b>',
                    }, {
                        parametro: 'campo-mensagem',
                        descricao: 'Controla a exibição do campo "Mensagem" no formulário.',
                        values: '<b>sim</b> ou <b>nao</b>',
                        padrao: '<b>sim</b>',
                    }, {
                        parametro: 'cor-fundo-campos',
                        descricao: 'Altera a cor de fundo dos campos do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#ffffff'
                    }, {
                        parametro: 'cor-borda-campos',
                        descricao: 'Altera a cor da borda dos campos do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#DCDFE6'
                    }, {
                        parametro: 'cor-fonte-campos',
                        descricao: 'Altera a cor da fonte dos campos do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#333333'
                    }, {
                        parametro: 'cor-label',
                        descricao: 'Altera a cor dos rótulos (labels) dos campos.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#606266'
                    }, {
                        parametro: 'cor-alertas',
                        descricao: 'Altera a cor dos alertas dos campos obrigátórios. A cor é aplicada na borda do campo e no dizer "obrigatório".',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#F56C6C'
                    }, {
                        parametro: 'texto-botao',
                        descricao: 'Altera o texto do botão do formulário.',
                        values: 'escreva seu texto no parâmetro',
                        padrao: 'Enviar'
                    }, {
                        parametro: 'cor-fundo-botao',
                        descricao: 'Altera a cor do botão do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#007bff'
                    }, {
                        parametro: 'cor-fonte-botao',
                        descricao: 'Altera a cor da fonte do botão do formulário.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#ffffff'
                    }, {
                        parametro: 'msg-sucesso',
                        descricao: 'Altera o texto da mensagem de sucesso que é exibida ao enviar o formulário.',
                        values: 'escreva sua mensagem no parâmetro',
                        padrao: 'Sua mensagem foi enviada com sucesso! Em breve um de nossos consultores entrará em contato.'
                    }, {
                        parametro: 'cor-msg-sucesso',
                        descricao: 'Altera a cor do texto da mensagem de sucesso.',
                        values: 'qualquer cor hexadecimal',
                        padrao: '#007bff'
                    }, {
                        parametro: 'fonte-msg-sucesso',
                        descricao: 'Altera o tamanho da fonte da mensagem de sucesso.',
                        values: 'qualquer valor em pixels',
                        padrao: '14px'
                    }, {
                        parametro: 'espaco-entre-campos',
                        descricao: 'Altera o espaçamento vertical entre os campos do formulário.',
                        values: 'qualquer valor em pixels',
                        padrao: '22px'
                    }, {
                        parametro: 'largura-form',
                        descricao: 'Altera a largura do container do formulário, a largura dos campos também serão afetadas.',
                        values: 'qualquer valor em pixels',
                        padrao: '600px'
                    }, {
                        parametro: 'layout-form',
                        descricao: 'Altera o layout do formulário para uma ou duas colunas.',
                        values: '<b>uma-coluna</b> ou <b>duas-colunas</b>',
                        padrao: '<b>duas-colunas</b>'
                    }, {
                        parametro: 'altura-iframe-desktop',
                        descricao: 'Altera a altura do iframe na versão desktop. OBS: Esta opção só é necessária quando inserido um  valor  no parâmetro "espaco-entre-campos" ou for necessário ajustar a altura do iframe.',
                        values: 'qualquer valor em pixels',
                        padrao: 'opcional'
                    }, {
                        parametro: 'altura-iframe-mobile',
                        descricao: 'Altera a altura do iframe na versão mobile. OBS: Esta opção só é necessária quando inserido um  valor  no parâmetro "espaco-entre-campos" ou for necessário ajustar a altura do iframe.',
                        values: 'qualquer valor em pixels',
                        padrao: 'opcional'
                    }]
                }
            },
            methods: {
                openSupport() {
                    window.open("https://suporte.agencialink.com.br/b2cor/plugin-wp-leads");
                }
            }
        });
    </script>

    <style>
        /* DEFAULTS */
        body {
            background: #eeeeee !important
        }

        h1 {
            font-family: 'Merriweather', serif;
            text-align: center;
            font-weight: 900 !important;
            margin: 0;
            font-size: 22px;
            background: #fcfcfc;
            padding: 22px 0 19px;
            border-bottom: 1px solid #e4e7ed;
        }

        h1 font {
            font-size: 12px;
            color: #ccc;
            letter-spacing: -0.3px;
            margin-left: 3px;
        }


        /* CONTAINER */

        #agencia-link {
            padding: 20px 0 0;
        }

        #agencia-link .alink-container {
            background: #FFF;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 1px 2px rgb(0 0 0 / 6%), 0 1px 3px rgb(0 0 0 / 10%);
            width: 800px;
        }

        /* HEAD */

        #agencia-link .alink-container .alink-head {
            padding: 20px 20px 10px;
            border-bottom: 1px solid #eee;
        }

        #agencia-link .alink-container .alink-head img {
            width: 230px;
        }

        #agencia-link .alink-container .alink-head .support {
            text-align: right;
        }

        /* BODY */

        #agencia-link .alink-container .alink-body .welcome {
            background: #007bff;
            text-align: center;
            color: #FFF;
            padding: 20px 20px;
        }

        #agencia-link .alink-container .alink-body .welcome p {
            font-size: 18px;
            margin: 0;
            font-weight: 600;
            font-family: 'Merriweather', serif;
        }

        #agencia-link .alink-container .alink-body .welcome .txt2 {
            font-size: 14px;
            font-weight: 300;
            margin-top: 6px;
        }

        #agencia-link .alink-container .alink-body .field {
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        #agencia-link .alink-container .alink-body .field .infos {
            font-size: 12px;
            background: #f6f6f6;
            padding: 6px 10px;
            border-radius: 3px;
            margin-bottom: 13px;
            color: #666;
        }

        #agencia-link .alink-container .alink-body .field>label {
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
            color: #333;
            font-weight: 700;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .copy {
            text-align: center;
            font-size: 16px;
            padding: 20px 20px;
            background: #fafafa;
            border-radius: 6px;
            color: #999;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .copy input {
            width: 60%;
            text-align: center;
            border-color: #eee;
            height: 52px;
            color: #333;
            font-size: 18px;
            letter-spacing: -0.5px;
            font-weight: 600;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .details li {
            margin-top: 20px;
            color: #666;
            font-size: 13px;
        }

        #agencia-link .alink-container .alink-body .field.shortcode .details li strong {
            font-style: italic;
            color: #333;
        }

        #agencia-link .alink-container .alink-body .field input[type=text],
        #agencia-link .alink-container .alink-body .field textarea {
            width: 100%;
            transition: .3s;
            border: 1px solid #ddd;
            outline-width: 0;
            background: #fefefe;
        }

        #agencia-link .alink-container .alink-body .field input[type=text]::placeholder,
        #agencia-link .alink-container .alink-body .field textarea::placeholder {
            font-size: 12px;
            color: #ddd;
        }

        #agencia-link .alink-container .alink-body .field textarea {
            min-height: auto;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            padding: 17px 20px 17px;
        }

        #agencia-link .alink-container .alink-body .field input[type=text]:focus,
        #agencia-link .alink-container .alink-body .field textarea:focus,
        #agencia-link .alink-container .alink-body .field input[type=text]:active,
        #agencia-link .alink-container .alink-body .field textarea:active {
            border: 1px solid #007bff;
            background: #fafafa;
        }

        /* TABS */

        #agencia-link .alink-container .alink-body .el-tabs__header {
            padding: 20px 20px 0 20px !important;
            margin: 0 !important;
        }

        /* TABELA */

        #agencia-link .alink-container .alink-body .el-table__header-wrapper {
            font-size: 12px;
        }

        #agencia-link .alink-container .alink-body .el-table .cell {
            line-height: 15px;
            font-size: 12px;
            word-break: break-word !important;
            font-family: "Open Sans";
        }

        #agencia-link .alink-container .alink-body .el-table input.param {
            border: 0 !important;
            padding: 0 3px;
            width: 121px !important;
            font-size: 12px !important;
            box-shadow: none !important;
            font-weight: 600;
        }

        /* #agencia-link .alink-container .alink-body .el-table__body-wrapper::-webkit-scrollbar {
            cursor: pointer;
            width: 17px;
        }

        #agencia-link .alink-container .alink-body .el-table__body-wrapper::-webkit-scrollbar-thumb {
            background-color: #eeeeee;
            cursor: pointer;
        }

        #agencia-link .alink-container .alink-body .el-table__body-wrapper::-webkit-scrollbar-track {
            background-color: #fafafa;
            cursor: pointer;
        } */

        #agencia-link .alink-container .alink-body .el-table input.param:focus {
            border: 0 !important;
            outline-width: 0;
        }

        #agencia-link .alink-container .alink-body .el-table thead {
            color: #000000;
            font-weight: 500;
        }

        #agencia-link .alink-container .alink-body .el-table td,
        #agencia-link .alink-container .alink-body .el-table th {
            padding: 7px 0;
        }

        /* FOOTER */

        #agencia-link .alink-container .alink-footer {
            padding: 20px;
        }

        #agencia-link .alink-container .alink-footer .btn {
            text-align: right;
        }

        #agencia-link .alink-container .alink-footer .version {
            font-weight: 600;
            color: #ddd;
            margin-top: 12px;
        }

        @media only screen and (min-device-width : 0px) and (max-width : 980px) {
            #agencia-link .alink-container {
                width: 100%;
            }
        }

        @media only screen and (min-device-width : 0px) and (max-width : 760px) {
            #agencia-link .alink-container .alink-head .logo-b2cor {
                display: none;
            }
        }
    </style>

<?php
}
