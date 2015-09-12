<?php namespace Tests\Site;

use Cart, Session;
use NovoTempo\Entities\Produto;

class CarrinhoTest extends \TestCase
{

    public function setUp()
    {
        parent::setUp();

        Cart::destroy();
    }

    public function test_adicionar_produto_a_uma_divisao_do_carrinho()
    {
        $produto = factory(Produto::class, 1)->create();

        Cart::division('fisicos')->add([
            'id'      => $produto->id,
            'title'   => $produto->titulo,
            'price'   => $produto->valor,
            'options' => $produto->toArray()
        ]);

        $this->assertEquals(1, Cart::division('fisicos')->count());
    }

    public function test_adicionar_produtos_a_duas_divisoes_do_carrinho()
    {
        $produto1 = factory(Produto::class, 1)->create();
        $produto2 = factory(Produto::class, 1)->create();

        Cart::division('fisicos')->add([
            'id'      => $produto1->id,
            'title'   => $produto1->titulo,
            'price'   => $produto1->valor,
            'options' => $produto1->toArray()
        ]);

        Cart::division('digitais')->add([
            'id'      => $produto2->id,
            'title'   => $produto2->titulo,
            'price'   => $produto2->valor,
            'options' => $produto2->toArray()
        ]);

        $this->assertEquals(1, Cart::division('fisicos')->count());
        $this->assertEquals(1, Cart::division('digitais')->count());
        $this->assertEquals(2, Cart::count());
    }

    public function test_obter_totais_por_divisoria_e_geral()
    {
        $produto1 = factory(Produto::class, 1)->create();
        $produto2 = factory(Produto::class, 1)->create();
        $produto3 = factory(Produto::class, 1)->create();
        $produto4 = factory(Produto::class, 1)->create();

        //Fisicos
        Cart::division('fisicos')->add([
            'id'      => $produto1->id,
            'title'   => $produto1->titulo,
            'price'   => 12.88,
            'options' => $produto1->toArray()
        ]);
        Cart::division('fisicos')->add([
            'id'      => $produto2->id,
            'title'   => $produto2->titulo,
            'price'   => 20.59,
            'options' => $produto2->toArray()
        ]);

        //Digitais
        Cart::division('digitais')->add([
            'id'      => $produto3->id,
            'title'   => $produto3->titulo,
            'price'   => 22.19,
            'options' => $produto3->toArray()
        ]);
        Cart::division('digitais')->add([
            'id'      => $produto4->id,
            'title'   => $produto4->titulo,
            'price'   => 13.33,
            'options' => $produto4->toArray()
        ]);

        $this->assertEquals(33.47, Cart::division('fisicos')->total());
        $this->assertEquals(35.52, Cart::division('digitais')->total());
        $this->assertEquals(68.99, Cart::total());
    }

    public function test_destruir_apenas_uma_das_divisorias()
    {
        $produto1 = factory(Produto::class, 1)->create();
        $produto2 = factory(Produto::class, 1)->create();
        $produto3 = factory(Produto::class, 1)->create();
        $produto4 = factory(Produto::class, 1)->create();

        //Fisicos
        Cart::division('fisicos')->add([
            'id'      => $produto1->id,
            'title'   => $produto1->titulo,
            'price'   => 12.88,
            'options' => $produto1->toArray()
        ]);
        Cart::division('fisicos')->add([
            'id'      => $produto2->id,
            'title'   => $produto2->titulo,
            'price'   => 20.59,
            'options' => $produto2->toArray()
        ]);

        //Digitais
        Cart::division('digitais')->add([
            'id'      => $produto3->id,
            'title'   => $produto3->titulo,
            'price'   => 22.19,
            'options' => $produto3->toArray()
        ]);
        Cart::division('digitais')->add([
            'id'      => $produto4->id,
            'title'   => $produto4->titulo,
            'price'   => 13.33,
            'options' => $produto4->toArray()
        ]);

        Cart::division('fisicos')->destroy();

        $this->assertEquals(0, Cart::division('fisicos')->count());
        $this->assertEquals(2, Cart::division('digitais')->count());

    }

    public function test_remover_item_do_carrinho()
    {
        $produto1 = factory(Produto::class, 1)->create();
        $produto2 = factory(Produto::class, 1)->create();

        $identifier1 = Cart::division('fisicos')->add([
            'id'      => $produto1->id,
            'title'   => $produto1->titulo,
            'price'   => 12.88,
            'options' => $produto1->toArray()
        ]);
        $identifier2 = Cart::division('fisicos')->add([
            'id'      => $produto2->id,
            'title'   => $produto2->titulo,
            'price'   => 20.59,
            'options' => $produto2->toArray()
        ]);

        $this->assertEquals(2, Cart::division('fisicos')->count());

        Cart::remove($identifier1);

        $this->assertEquals(1, Cart::division('fisicos')->count());

        Cart::division('fisicos')->remove($identifier2);

        $this->assertEquals(0, Cart::division('fisicos')->count());

    }

    public function test_se_nao_foi_adicionada_nenhuma_division_ainda_retorna_zero_de_total()
    {
        Cart::destroy();

        $this->assertEquals(0, Cart::total());
    }

    public function test_listar_carrinho_com_metodo_content()
    {

        $identifier1 = Cart::division('fisicos')->add([
            'id'       => 1,
            'title'    => 'Teste 1',
            'price'    => 12.88,
            'quantity' => 1,
            'options'  => [
                'artista' => [
                    'nome' => 'Wanderley Cardoso'
                ]
            ]
        ]);

        $itens = Cart::division('fisicos')->content();

        foreach ($itens as $item) {
            $this->assertEquals($identifier1, $item->identifier);
            $this->assertEquals('Teste 1', $item->title);
            $this->assertEquals(12.88, $item->price);
            $this->assertEquals('Wanderley Cardoso', $item->options['artista']['nome']);
        }

    }

    public function test_verificar_se_o_carrinho_esta_vazio()
    {
        Cart::destroy();

        $this->assertTrue(Cart::division('fisicos')->isEmpty());
        $this->assertTrue(Cart::isEmpty());
    }

    public function test_se_o_carrinho_estiver_vazio_retorna_um_array_vazio()
    {
        Cart::destroy();

        $this->assertEquals([], Cart::division('fisicos')->content());
    }

    public function test_calcular_frete()
    {
        Cart::frete(5);

        $this->assertEquals(5, Cart::frete());
    }

    public function test_se_frete_ainda_nao_calculado_retornar_zero()
    {
        Session::forget('cart.frete');

        $this->assertEquals(0, Cart::frete());
    }

    public function test_se_a_divisao_nao_possui_produtos_o_total_eh_zero()
    {
        $this->assertEquals(0, Cart::division('nenhum')->total());
    }

    public function test_ao_inserir_um_produto_que_ja_esta_apenas_aumentar_quantidade()
    {
        $identifier1 = Cart::division('fisicos')->add([
            'id'       => 1,
            'title'    => 'Teste 1',
            'price'    => 12.88,
            'quantity' => 1,
            'options'  => [
                'artista' => [
                    'nome' => 'Wanderley Cardoso'
                ]
            ]
        ]);

        $identifier2 = Cart::division('fisicos')->add([
            'id'       => 1,
            'title'    => 'Teste 1',
            'price'    => 12.88,
            'quantity' => 1,
            'options'  => [
                'artista' => [
                    'nome' => 'Wanderley Cardoso'
                ]
            ]
        ]);

        $this->assertEquals(1, Cart::division('fisicos')->count());
        $this->assertEquals(25.76, Cart::division('fisicos')->total());
        $this->assertEquals($identifier1, $identifier2);
    }

    public function test_remover_apenas_uma_unidade_do_produto()
    {
    	$identifier1 = Cart::division('fisicos')->add([
            'id'       => 1,
            'title'    => 'Teste 1',
            'price'    => 12.88,
            'quantity' => 1,
            'options'  => [
                'artista' => [
                    'nome' => 'Wanderley Cardoso'
                ]
            ]
        ]);

        $identifier2 = Cart::division('fisicos')->add([
            'id'       => 1,
            'title'    => 'Teste 1',
            'price'    => 12.88,
            'quantity' => 1,
            'options'  => [
                'artista' => [
                    'nome' => 'Wanderley Cardoso'
                ]
            ]
        ]);

        $this->assertEquals(25.76, Cart::division('fisicos')->total());
        Cart::removeUnit($identifier2);
        $this->assertEquals(12.88, Cart::division('fisicos')->total());
    }


}