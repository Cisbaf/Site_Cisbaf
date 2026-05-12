<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.7' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'assets/css/editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				HELLO_THEME_STYLE_URL . 'reset.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				HELLO_THEME_STYLE_URL . 'theme.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				HELLO_THEME_STYLE_URL . 'header-footer.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();





























/**
 * CÓDIGO CUSTOMIZADO: LISTAGEM DE LICITAÇÕES (CISBAF)
 * Este código cria o shortcode [lista_licitacoes] para usar no Elementor.
 */


// --- PARTE 1: SIDEBAR DE FILTROS (ANO, MODALIDADE, STATUS) ---
function renderizar_filtros_licitacoes() {
    // Slugs das taxonomias conforme seus prints do CPT UI
    $filtros_config = array(
        'ano'        => 'Ano',
        'modalidade'  => 'Modalidade',
        'status_lic'  => 'Status' // <--- Corrigido para bater com seu print image_568027.png
    );
    
    $html = '<div class="sidebar-filtros" style="width: 250px; background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; font-family: Arial, sans-serif;">';
    
    // Botão Limpar
    $current_url = strtok($_SERVER["REQUEST_URI"], '?'); 
    $html .= '<a href="'.$current_url.'" style="display:block; margin-bottom:20px; text-align:center; background:#dc3545; color:#fff; text-decoration:none; padding:8px; border-radius:4px; font-size:13px; font-weight:bold;">LIMPAR FILTROS</a>';

    foreach ($filtros_config as $tax => $titulo) {
        // Buscamos os termos cadastrados
        $termos = get_terms(array('taxonomy' => $tax, 'hide_empty' => false));

        $html .= '<h4 style="margin: 15px 0 10px 0; color:#333; border-bottom: 2px solid #26b99a; padding-bottom:5px;">' . $titulo . '</h4>';
        
        if (is_wp_error($termos) || empty($termos)) {
            $html .= '<p style="font-size:11px; color:#999; font-style:italic;">Nenhum item cadastrado em '.$titulo.'.</p>';
            continue;
        }

        $html .= '<ul style="list-style:none; padding:0; margin:0 0 20px 0;">';
        foreach ($termos as $termo) {
            // Conversão de segurança para evitar os Warnings
            $t = (object) $termo; 

            $link = add_query_arg($tax . '_filtro', $t->slug);
            $active = (isset($_GET[$tax . '_filtro']) && $_GET[$tax . '_filtro'] == $t->slug);

            $html .= '<li style="margin-bottom:5px; font-size:14px;">';
            $html .= '  <a href="' . $link . '" style="text-decoration:none; color:'.($active ? '#26b99a' : '#555').'; font-weight:'.($active ? 'bold' : 'normal').'; display: flex; justify-content: space-between;">';
            $html .= '    <span>' . $t->name . '</span>';
            $html .= '    <span style="background:#eee; padding:0 6px; border-radius:10px; font-size:11px; color:#666;">' . $t->count . '</span>';
            $html .= '  </a>';
            $html .= '</li>';
        }
        $html .= '</ul>';
    }
    
    $html .= '</div>';
    return $html;
}
add_shortcode('filtros_licitacoes', 'renderizar_filtros_licitacoes');


// --- PARTE 2: LISTAGEM COM LÓGICA DE FILTROS MÚLTIPLOS ---
function renderizar_licitacoes_custom() {
    if (!function_exists('get_field')) return 'Ative o ACF';

    // Captura os filtros da URL
    $filtro_ano   = isset($_GET['ano_filtro']) ? sanitize_text_field($_GET['ano_filtro']) : '';
    $filtro_mod   = isset($_GET['modalidade_filtro']) ? sanitize_text_field($_GET['modalidade_filtro']) : '';
    $filtro_stat  = isset($_GET['status_lic_filtro']) ? sanitize_text_field($_GET['status_lic_filtro']) : '';

    $args = array(
        'post_type'      => 'licitacao', 
        'posts_per_page' => 20, 
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    // Monta a Query de Taxonomia dinamicamente
    $tax_query = array('relation' => 'AND');

    if (!empty($filtro_ano)) {
        $tax_query[] = array('taxonomy' => 'ano', 'field' => 'slug', 'terms' => $filtro_ano);
    }
    if (!empty($filtro_mod)) {
        $tax_query[] = array('taxonomy' => 'modalidade', 'field' => 'slug', 'terms' => $filtro_mod);
    }
    if (!empty($filtro_stat)) {
        $tax_query[] = array('taxonomy' => 'status_lic', 'field' => 'slug', 'terms' => $filtro_stat);
    }

    if (count($tax_query) > 1) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);
    $html = '<div class="lista-container-cisbaf" style="width: 100%; font-family: Arial, sans-serif;">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $abertura = get_field('abertura_em');
            $objeto   = get_field('objeto');
            $data_pub = get_the_date('d/m/Y');
            
            // Busca o Status para o Badge colorido
            $status_field = get_field('status');
            $status_nome  = 'Não definido';
            $bg_status    = '#6c757d';

            if ($status_field) {
                $term = get_term(is_array($status_field) ? $status_field[0] : $status_field);
                if ($term && !is_wp_error($term)) {
                    $status_nome = $term->name;
                    if(strtolower($status_nome) == 'aberta') $bg_status = '#00c853';
                }
            }

            // HTML do Card (mantendo o seu padrão)
            $html .= '<div class="item-licitacao" style="border: 1px solid #dee2e6; margin-bottom: 20px; background: #fff;">';
            $html .= '  <div style="background: #f8f9fa; padding: 10px 15px; border-bottom: 1px solid #dee2e6;">';
            $html .= '    <h3 style="margin:0; color: #555; font-size: 22px; font-weight: normal;">' . get_the_title() . '</h3>';
            $html .= '  </div>';
            $html .= '  <div style="display: flex; flex-wrap: wrap; border-bottom: 1px solid #dee2e6;">';
            $html .= '    <div style="flex: 1; min-width: 200px; padding: 8px 15px; border-right: 1px solid #dee2e6;">';
            $html .= '      <strong>Status:</strong> <span style="background: '.$bg_status.'; color:#fff; padding: 2px 6px; border-radius: 3px; font-size: 12px; font-weight: bold;">'.$status_nome.'</span>';
            $html .= '    </div>';
            $html .= '    <div style="flex: 1; min-width: 250px; padding: 8px 15px;">';
            $html .= '      <strong>Abertura:</strong> ' . ($abertura ? $abertura : '--/--/----') . '';
            $html .= '    </div>';
            $html .= '  </div>';
            $html .= '  <div style="padding: 8px 15px; border-bottom: 1px solid #dee2e6; color: #444; font-size: 14px;">';
            $html .= '    <strong>Publicado em:</strong> ' . $data_pub;
            $html .= '  </div>';
            $html .= '  <div style="padding: 15px; color: #666; font-size: 15px; line-height: 1.4;">';
            $html .= '    <div style="margin-bottom: 5px; color: #444;"><strong>Objeto:</strong></div>';
            $html .= '    ' . ($objeto ? $objeto : 'Sem descrição.') . '';
            $html .= '  </div>';
            $html .= '  <div style="padding: 15px; text-align: center; border-top: 1px solid #eee;">';
            $html .= '    <a href="'.get_permalink().'" style="background: #26b99a; color: #fff; text-decoration: none; padding: 6px 20px; border-radius: 4px; font-weight: bold; font-size: 14px; display: inline-block;">+ DETALHES</a>';
            $html .= '  </div>';
            $html .= '</div>'; 
        }
        wp_reset_postdata();
    } else {
        $html .= '<div style="padding:20px; background:#fff3cd; color:#856404; border:1px solid #ffeeba;">Nenhuma licitação encontrada para os filtros selecionados.</div>';
    }

    $html .= '</div>';
    return $html;
}
add_shortcode('lista_licitacoes', 'renderizar_licitacoes_custom');

// --- PARTE 3: TEMPLATE DA PÁGINA DE DETALHES (SINGLE) ---
function gerar_template_single_licitacao($content) {
    if ( is_singular('licitacao') && in_the_loop() && is_main_query() ) {
        
        // Pega os dados dinâmicos
        $abertura = get_field('abertura_em');
        $objeto   = get_field('objeto');
        $data_pub = get_the_date('d/m/Y');
        $arquivos_raw = get_field('lista_de_documentos'); // Campo que você criou
        
        // Lógica de Status (conforme sua taxonomia status_lic)
        $status_field = get_field('status');
        $status_nome  = 'Não definido';
        $bg_status    = '#6c757d';

        if ($status_field) {
            $term = get_term(is_array($status_field) ? $status_field[0] : $status_field);
            if ($term && !is_wp_error($term)) {
                $status_nome = $term->name;
                // Cores baseadas no que você marcou no painel
                if(strtolower($status_nome) == 'aberta' || strtolower($status_nome) == 'ativo') {
                    $bg_status = '#00c853';
                }
            }
        }

        // --- INÍCIO DO HTML ---
        $html = '<div style="max-width: 1000px; margin: 20px auto; font-family: Arial, sans-serif; border: 1px solid #ddd; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">';
        
        // Cabeçalho
        $html .= '  <div style="background: #f8f9fa; padding: 30px; border-bottom: 4px solid #26b99a;">';
        $html .= '      <h1 style="margin:0; color:#333; font-size: 26px;">' . get_the_title() . '</h1>';
        $html .= '  </div>';

        // Infos Rápidas
        $html .= '  <div style="display: flex; flex-wrap: wrap; background: #eee; border-bottom: 1px solid #ddd;">';
        $html .= '      <div style="padding: 15px 25px; border-right: 1px solid #ddd; flex: 1; min-width: 150px;">';
        $html .= '          <small style="color: #888; text-transform: uppercase; font-size: 10px; font-weight: bold;">Status</small>';
        $html .= '          <div style="margin-top:5px;"><span style="background: '.$bg_status.'; color:#fff; padding: 4px 10px; border-radius: 3px; font-size: 13px; font-weight: bold;">'.$status_nome.'</span></div>';
        $html .= '      </div>';
        $html .= '      <div style="padding: 15px 25px; border-right: 1px solid #ddd; flex: 1; min-width: 150px;">';
        $html .= '          <small style="color: #888; text-transform: uppercase; font-size: 10px; font-weight: bold;">Abertura</small>';
        $html .= '          <div style="margin-top:5px; font-size: 16px; font-weight: bold; color: #444;">'.($abertura ? $abertura : '--/--/----').'</div>';
        $html .= '      </div>';
        $html .= '      <div style="padding: 15px 25px; flex: 1; min-width: 150px;">';
        $html .= '          <small style="color: #888; text-transform: uppercase; font-size: 10px; font-weight: bold;">Publicado em</small>';
        $html .= '          <div style="margin-top:5px; font-size: 16px; color: #666;">'.$data_pub.'</div>';
        $html .= '      </div>';
        $html .= '  </div>';

        // Objeto
        $html .= '  <div style="padding: 30px;">';
        $html .= '      <h3 style="margin-top:0; color: #26b99a; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Objeto</h3>';
        $html .= '      <div style="line-height: 1.6; color: #555; font-size: 15px;">' . nl2br($objeto) . '</div>';
        $html .= '  </div>';

        // --- SEÇÃO DE DOCUMENTOS (Mágica para transformar links do editor em botões) ---
        if ($arquivos_raw) {
            $html .= '  <div style="padding: 30px; background: #fcfcfc; border-top: 1px solid #eee;">';
            $html .= '      <h3 style="margin-top:0; color: #333; font-size: 18px; margin-bottom: 20px;">Documentos Disponíveis</h3>';
            
            // Aqui pegamos o conteúdo do editor e injetamos estilos CSS nos links <a> automaticamente
            $estilo_botao = 'display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 20px; border: 1px solid #e0e0e0; border-left: 5px solid #26b99a; border-radius: 5px; margin-bottom: 10px; text-decoration: none; color: #333; font-weight: bold; transition: background 0.3s;';
            
            $arquivos_formatados = str_replace('<a ', '<a style="'.$estilo_botao.'" ', $arquivos_raw);
            
            // Adiciona um aviso visual de "Download" após o texto do link
            $arquivos_formatados = str_replace('</a>', ' <span style="background:#26b99a; color:#fff; padding: 5px 12px; border-radius:4px; font-size:12px;">BAIXAR</span></a>', $arquivos_formatados);

            $html .= '<div class="lista-documentos">' . $arquivos_formatados . '</div>';
            $html .= '  </div>';
        }

        $html .= '  <div style="padding: 20px; text-align: center; background: #f8f9fa; border-top: 1px solid #eee;">';
        $html .= '      <a href="javascript:history.back()" style="color: #26b99a; text-decoration: none; font-weight: bold; font-size: 14px;">← Voltar para a Listagem</a>';
        $html .= '  </div>';
        $html .= '</div>';

        return $html;
    }
    return $content;
}
add_filter('the_content', 'gerar_template_single_licitacao');















/**
 * CÓDIGO CUSTOMIZADO: PORTAL DE NOTÍCIAS CISBAF (V2 - UI/UX MELHORADA)
 */

function renderizar_portal_noticias_cisbaf() {
    // 1. Lógica de Filtros e Busca
    $categoria_slug = isset($_GET['cat_noticia']) ? sanitize_text_field($_GET['cat_noticia']) : '';
    $termo_busca    = isset($_GET['s_noticia']) ? sanitize_text_field($_GET['s_noticia']) : '';
    $current_url    = strtok($_SERVER["REQUEST_URI"], '?');

    // 2. CSS Modernizado
    $html = '
    <style>
        .noticias-container { display: flex; flex-wrap: wrap; gap: 40px; font-family: "Segoe UI", Roboto, Arial, sans-serif; margin: 30px 0; color: #333; }
        
        /* Sidebar e Card de Busca */
        .sidebar-noticias { flex: 0 0 320px; }
        .card-busca { 
            background: #f8fafc; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            padding: 25px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .card-busca h4 { 
            margin: 0 0 20px 0; 
            font-size: 14px; 
            color: #1e40af; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-busca .campo-grupo { margin-bottom: 15px; }
        .card-busca label { display: block; font-size: 13px; font-weight: 600; color: #64748b; margin-bottom: 8px; }
        
        .card-busca input[type="text"] { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e2e8f0; 
            border-radius: 8px; 
            font-size: 15px; 
            transition: all 0.3s;
            box-sizing: border-box;
        }
        .card-busca input[type="text"]:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        
        .btn-buscar { 
            width: 100%; 
            background: #1e40af; 
            color: white; 
            border: none; 
            padding: 12px; 
            border-radius: 8px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s; 
            text-transform: uppercase;
            font-size: 14px;
        }
        .btn-buscar:hover { background: #1e3a8a; transform: translateY(-1px); }
        
        .btn-limpar-filtro { 
            display: block; 
            text-align: center; 
            margin-top: 15px; 
            font-size: 12px; 
            color: #ef4444; 
            text-decoration: none; 
            font-weight: 600;
        }
        .btn-limpar-filtro:hover { text-decoration: underline; }

        /* Categorias na Sidebar */
        .titulo-lateral { font-size: 18px; font-weight: 700; color: #1e293b; margin: 30px 0 15px; padding-bottom: 10px; border-bottom: 2px solid #3b82f6; }
        .lista-categorias { list-style: none; padding: 0; margin: 0; }
        .lista-categorias li { margin-bottom: 8px; }
        .lista-categorias a { 
            display: flex; 
            justify-content: space-between; 
            text-decoration: none; 
            color: #475569; 
            padding: 8px 12px; 
            border-radius: 6px; 
            font-size: 14px; 
            transition: 0.2s;
        }
        .lista-categorias a:hover { background: #eff6ff; color: #1e40af; }
        .cat-ativa { background: #1e40af !important; color: white !important; font-weight: 600; }

        /* Lista de Notícias */
        .feed-noticias { flex: 1; min-width: 300px; }
        .card-noticia { 
            display: flex; 
            gap: 25px; 
            margin-bottom: 30px; 
            padding-bottom: 30px; 
            border-bottom: 1px solid #f1f5f9;
            transition: 0.3s;
        }
        .card-noticia:hover .img-link img { transform: scale(1.05); }
        .img-link { flex: 0 0 240px; overflow: hidden; border-radius: 10px; height: 150px; }
        .img-link img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        
        .info-noticia { flex: 1; }
        .info-noticia .meta-cat { 
            font-size: 12px; 
            font-weight: 800; 
            color: #3b82f6; 
            text-transform: uppercase; 
            margin-bottom: 8px; 
            display: block; 
        }
        .info-noticia h3 { margin: 0 0 12px 0; line-height: 1.3; }
        .info-noticia h3 a { text-decoration: none; color: #1e293b; font-size: 22px; font-weight: 700; transition: 0.2s; }
        .info-noticia h3 a:hover { color: #1e40af; }
        .info-noticia p { color: #64748b; font-size: 15px; line-height: 1.5; margin: 0; }

        /* Paginação */
        .paginacao-custom { margin-top: 40px; display: flex; justify-content: center; gap: 8px; }
        .paginacao-custom .page-numbers { 
            padding: 8px 16px; 
            border-radius: 6px; 
            background: #fff; 
            border: 1px solid #e2e8f0; 
            color: #1e40af; 
            text-decoration: none; 
            font-weight: 600; 
        }
        .paginacao-custom .current { background: #1e40af; color: white; border-color: #1e40af; }

        @media (max-width: 850px) {
            .noticias-container { flex-direction: column; }
            .sidebar-noticias { flex: 1; order: 2; }
            .feed-noticias { order: 1; }
            .card-noticia { flex-direction: column; gap: 15px; }
            .img-link { flex: 1; width: 100%; height: 200px; }
        }
    </style>';

    $html .= '<div class="noticias-container">';

    // --- SIDEBAR (BUSCA E CATEGORIAS) ---
    $html .= '<aside class="sidebar-noticias">';
    
    // Card de Busca
    $html .= '<div class="card-busca">';
    $html .= '<form method="GET" action="'.$current_url.'">';
    $html .= '<div class="campo-grupo">';
        $html .= '<label for="s_noticia">O que você procura?</label>';
        $html .= '<input type="text" id="s_noticia" name="s_noticia" value="'.esc_attr($termo_busca).'" placeholder="Ex: Editais, Avisos...">';
    $html .= '</div>';
    
    if($categoria_slug) {
        $html .= '<input type="hidden" name="cat_noticia" value="'.esc_attr($categoria_slug).'">';
    }

    $html .= '<button type="submit" class="btn-buscar">Aplicar Filtros</button>';
    
    if($termo_busca || $categoria_slug) {
        $html .= '<a href="'.$current_url.'" class="btn-limpar-filtro">✕ Limpar filtros</a>';
    }
    $html .= '</form>';
    $html .= '</div>';

    // Categorias
    $html .= '<h4 class="titulo-lateral">Categorias</h4>';
    $categorias = get_categories(array('hide_empty' => true));
    $html .= '<ul class="lista-categorias">';
    foreach ($categorias as $cat) {
        $link = add_query_arg(array('cat_noticia' => $cat->slug, 's_noticia' => $termo_busca), $current_url);
        $active_class = ($categoria_slug == $cat->slug) ? 'cat-ativa' : '';
        $html .= '<li><a href="'.$link.'" class="'.$active_class.'"><span>'.$cat->name.'</span> <small>'.$cat->count.'</small></a></li>';
    }
    $html .= '<ul>';
    $html .= '</aside>';

    // --- LISTAGEM DE POSTS ---
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 8,
        'paged'          => $paged,
        'category_name'  => $categoria_slug,
        's'              => $termo_busca 
    );

    $query = new WP_Query($args);

    $html .= '<section class="feed-noticias">';
    
    if ($termo_busca) {
        $html .= '<div style="margin-bottom: 25px; font-size: 18px;">Resultados para: <strong>' . esc_html($termo_busca) . '</strong></div>';
    }

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $thumb = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: 'https://via.placeholder.com/600x400?text=Sem+Imagem';
            $cats_post = get_the_category();
            $cat_nome = !empty($cats_post) ? $cats_post[0]->name : 'Geral';
            $resumo = wp_trim_words(get_the_excerpt(), 20);

            $html .= '
            <article class="card-noticia">
                <div class="img-link">
                    <a href="'.get_permalink().'"><img src="'.$thumb.'" alt="'.get_the_title().'"></a>
                </div>
                <div class="info-noticia">
                    <span class="meta-cat">'.$cat_nome.'</span>
                    <h3><a href="'.get_permalink().'">'.get_the_title().'</a></h3>
                    <p>'.$resumo.'</p>
                </div>
            </article>';
        }

        $html .= '<div class="paginacao-custom">';
        $html .= paginate_links(array(
            'total'    => $query->max_num_pages,
            'current'  => $paged,
            'add_args' => array('cat_noticia' => $categoria_slug, 's_noticia' => $termo_busca),
            'prev_text' => '«',
            'next_text' => '»'
        ));
        $html .= '</div>';

        wp_reset_postdata();
    } else {
        $html .= '
        <div style="text-align:center; padding: 50px; background: #f8fafc; border-radius: 12px; border: 2px dashed #e2e8f0;">
            <p style="font-size: 18px; color: #64748b;">Nenhuma notícia encontrada para estes filtros.</p>
            <a href="'.$current_url.'" style="color: #1e40af; font-weight: bold;">Ver todas as notícias</a>
        </div>';
    }
    $html .= '</section></div>';

    return $html;
}
add_shortcode('lista_noticias_cisbaf', 'renderizar_portal_noticias_cisbaf');
















/**
 * CÓDIGO CUSTOMIZADO: SIDEBAR (NOTÍCIAS RECENTES)
 * Shortcode: [sidebar_noticias_cisbaf]
 */

function renderizar_sidebar_leia_mais_cisbaf() {
    // 1. Estilos CSS específicos para a Sidebar
    $html = '
    <style>
        .sidebar-leia-mais {
            max-width: 100%;
            font-family: Arial, sans-serif;
            padding: 10px;
        }
        .sidebar-leia-mais .header-sidebar {
            text-align: center;
            color: #0056b3;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
        .item-sidebar-noticia {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .item-sidebar-noticia:last-child {
            border-bottom: none;
        }
        .sidebar-data {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #777;
            font-size: 13px;
            margin-bottom: 3px;
        }
        .sidebar-categoria {
            display: block;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .sidebar-titulo {
            margin: 0;
            line-height: 1.3;
        }
        .sidebar-titulo a {
            text-decoration: none;
            color: #003366; /* Azul escuro igual ao site */
            font-size: 18px;
            font-weight: 500;
            text-transform: uppercase;
            transition: color 0.2s;
        }
        .sidebar-titulo a:hover {
            color: #007bff;
        }
    </style>';



    // 2. Query para buscar as últimas 5 notícias
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Pega a categoria
            $categories = get_the_category();
            $cat_nome = !empty($categories) ? $categories[0]->name : 'Notícias';
            
            // Formata a data (Ex: 06/05/2026 09:02)
            $data_formatada = get_the_date('d/m/Y H:i');

            $html .= '
            <div class="item-sidebar-noticia">
                <span class="sidebar-data">
                    <i>🕒</i> ' . $data_formatada . '
                </span>
                <span class="sidebar-categoria">' . $cat_nome . '</span>
                <h4 class="sidebar-titulo">
                    <a href="' . get_permalink() . '">' . get_the_title() . '</a>
                </h4>
            </div>';
        }
        wp_reset_postdata();
    } else {
        $html .= '<p>Nenhuma notícia recente.</p>';
    }

    $html .= '</div>';

    return $html;
}
add_shortcode('sidebar_noticias_cisbaf', 'renderizar_sidebar_leia_mais_cisbaf');










/**
 * SHORTCODE: EXIBIR DATA DO POST
 * Uso: Publicado em: [data_post] | Fonte/Agência: Cisbaf
 */
function shortcode_data_publicacao() {
    // Pega a data e hora do post atual
    $data = get_the_date('d/m/Y');
    $hora = get_the_time('H:i');

    return 'Publicado em: ' . $data . ' ' . $hora . ' | Fonte/Agência: Cisbaf';
}
add_shortcode('data_post', 'shortcode_data_publicacao');