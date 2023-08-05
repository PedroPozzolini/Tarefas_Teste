<?php

namespace Tests\Unit;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Teste de CRUD para a classe Product.
     * 
     * @return void
    */
    public function testCRUD()
    {
        // Teste Create (Criação)
        $productsData = [];
        for ($i= 1; $i <= 10; $i++) { 
            $productsData[] = [
                'name' => 'Produto ' . $i,
                'price' => 10.00 + $i,
                'description' => 'Descrição do Produto' . $i
            ];
        }

        foreach ($productsData as $productData) {
            $product = Product::create($productData);
            $this->assertInstanceof (Product::class, $product);
            $this->assertDatabaseHas ('products', $productData);
        }

        // Teste Read (Leitura)
        $products = Product::all();
        $this->assertCount (10, $products);

        foreach ($productsData as $index => $productData) {
            $this->assertEquals($productData['name'], $products[$index]->name);
            $this->assertEquals($productData['price'], $products[$index]->price);
            $this->assertEquals($productData['description'], $products[$index]->description);
        }

    // Teste Update (Atualização)
    foreach ($products as $index => $product) {
        $updatedProductData = [
            'name' => 'Produto Atualizado ' . ($index + 1),
            'price' => 20.0 + $index,
            'description' => 'Nova descrição do Produto' . ($index + 1),
        ];

        $product->update($updatedProductData);
        $this->assertDatabaseHas('products', $updatedProductData);
    }

        // Teste Delete (Remoção)
        foreach ($products as $product) { 
            $product->delete();
            $this->assertDeleted ($product);
        }

        // Verifica se a tabela está vazia após a remoção
        $productsAfterDeletion = Product::all();
        $this->assertCount(0, $productsAfterDeletion);
    }
}