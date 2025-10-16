<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrazilianProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();
        Category::truncate();
        Brand::truncate();

        $categories = $this->createCategories();
        $brands = $this->createBrands();
        $this->createProducts($categories, $brands);
        
        $this->command->info('✅ Brazilian products seeded successfully!');
        $this->command->info("📊 {$categories->count()} categories, {$brands->count()} brands, " . Product::count() . " products");
    }

    private function createCategories(): \Illuminate\Support\Collection
    {
        $categoriesData = [
            ['name' => 'Eletrônicos', 'description' => 'Smartphones, tablets, notebooks e acessórios tecnológicos'],
            ['name' => 'Eletrodomésticos', 'description' => 'Geladeira, fogão, micro-ondas e linha branca em geral'],
            ['name' => 'Moda Masculina', 'description' => 'Roupas, calçados e acessórios para homens'],
            ['name' => 'Moda Feminina', 'description' => 'Roupas, calçados e acessórios para mulheres'],
            ['name' => 'Casa e Decoração', 'description' => 'Móveis, decoração e utilidades domésticas'],
            ['name' => 'Esportes e Lazer', 'description' => 'Artigos esportivos e equipamentos de lazer'],
            ['name' => 'Beleza e Cuidados', 'description' => 'Cosméticos, perfumes e produtos de cuidados pessoais'],
            ['name' => 'Livros e Papelaria', 'description' => 'Livros, material escolar e de escritório'],
            ['name' => 'Brinquedos e Games', 'description' => 'Brinquedos, jogos e videogames'],
            ['name' => 'Alimentação', 'description' => 'Alimentos, bebidas e produtos gourmet'],
        ];

        return collect($categoriesData)->map(function ($data) {
            return Category::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'],
                'active' => true,
            ]);
        });
    }

    private function createBrands(): \Illuminate\Support\Collection
    {
        $brandsData = [
            // Marcas brasileiras icônicas
            'Natura', 'O Boticário', 'Havaianas', 'Melissa', 'Tok&Stok', 'Casas Bahia',
            'Magazine Luiza', 'Americanas', 'Submarino', 'Saraiva', 'Riachuelo', 'C&A',
            'Renner', 'Marisa', 'Centauro', 'Netshoes', 'Tramontina', 'Caloi',
            
            // Marcas internacionais populares no Brasil
            'Samsung', 'Apple', 'Motorola', 'LG', 'Sony', 'Philips', 'Electrolux',
            'Brastemp', 'Consul', 'Nike', 'Adidas', 'Puma', 'Coca-Cola', 'Nestlé',
            'Unilever', 'P&G', 'Johnson & Johnson', 'L\'Oréal', 'Avon', 'Dell',
            'Lenovo', 'HP', 'Asus', 'Xiaomi', 'Multilaser', 'Positivo',
        ];

        return collect($brandsData)->map(function ($brandName) {
            return Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
                'description' => "Marca reconhecida no mercado brasileiro",
                'active' => true,
            ]);
        });
    }

    private function createProducts($categories, $brands): void
    {
        $productsData = [
            // Eletrônicos - Produtos mais vendidos no Brasil
            ['name' => 'iPhone 15 Pro Max 256GB Space Black', 'category' => 'Eletrônicos', 'brand' => 'Apple', 'price' => 8999.00],
            ['name' => 'Samsung Galaxy S24 Ultra 512GB Titanium', 'category' => 'Eletrônicos', 'brand' => 'Samsung', 'price' => 7499.00],
            ['name' => 'Motorola Edge 40 Pro 256GB Eclipse Black', 'category' => 'Eletrônicos', 'brand' => 'Motorola', 'price' => 3299.00],
            ['name' => 'Notebook Dell Inspiron 15 i5 8GB 256GB SSD', 'category' => 'Eletrônicos', 'brand' => 'Dell', 'price' => 2899.00],
            ['name' => 'Smart TV LG 55" 4K OLED C3 WebOS', 'category' => 'Eletrônicos', 'brand' => 'LG', 'price' => 4599.00],
            ['name' => 'Tablet Samsung Galaxy Tab S9 11" 256GB', 'category' => 'Eletrônicos', 'brand' => 'Samsung', 'price' => 2199.00],
            ['name' => 'Xiaomi Redmi Note 13 Pro 256GB', 'category' => 'Eletrônicos', 'brand' => 'Xiaomi', 'price' => 1899.00],

            // Eletrodomésticos - Linha branca brasileira
            ['name' => 'Geladeira Brastemp Frost Free Duplex 400L Inox', 'category' => 'Eletrodomésticos', 'brand' => 'Brastemp', 'price' => 2799.00],
            ['name' => 'Fogão Consul 5 Bocas Mesa de Vidro Preto', 'category' => 'Eletrodomésticos', 'brand' => 'Consul', 'price' => 899.00],
            ['name' => 'Micro-ondas Electrolux 31L Prata', 'category' => 'Eletrodomésticos', 'brand' => 'Electrolux', 'price' => 549.00],
            ['name' => 'Máquina de Lavar Brastemp 12kg Turbo Economia', 'category' => 'Eletrodomésticos', 'brand' => 'Brastemp', 'price' => 1899.00],
            ['name' => 'Air Fryer Philips Walita Viva Collection 4L', 'category' => 'Eletrodomésticos', 'brand' => 'Philips', 'price' => 399.00],
            ['name' => 'Lava-Louças Electrolux 14 Serviços Inox', 'category' => 'Eletrodomésticos', 'brand' => 'Electrolux', 'price' => 2299.00],

            // Moda Masculina - Tendências brasileiras
            ['name' => 'Tênis Nike Air Max 270 Masculino Preto', 'category' => 'Moda Masculina', 'brand' => 'Nike', 'price' => 699.00],
            ['name' => 'Camisa Polo Lacoste Masculina Azul Marinho', 'category' => 'Moda Masculina', 'brand' => 'Lacoste', 'price' => 399.00],
            ['name' => 'Calça Jeans Levi\'s 511 Slim Fit Azul', 'category' => 'Moda Masculina', 'brand' => 'Levi\'s', 'price' => 299.00],
            ['name' => 'Relógio Casio G-Shock Digital Preto', 'category' => 'Moda Masculina', 'brand' => 'Casio', 'price' => 459.00],
            ['name' => 'Jaqueta Adidas Originals Trefoil Preta', 'category' => 'Moda Masculina', 'brand' => 'Adidas', 'price' => 349.00],

            // Moda Feminina - Marcas brasileiras icônicas
            ['name' => 'Sandália Havaianas Slim Crystal Dourada', 'category' => 'Moda Feminina', 'brand' => 'Havaianas', 'price' => 39.90],
            ['name' => 'Sapatilha Melissa Ultragirl Sweet Rosa', 'category' => 'Moda Feminina', 'brand' => 'Melissa', 'price' => 199.00],
            ['name' => 'Vestido Renner Midi Estampado Floral', 'category' => 'Moda Feminina', 'brand' => 'Renner', 'price' => 89.90],
            ['name' => 'Bolsa Feminina C&A Couro Sintético Preta', 'category' => 'Moda Feminina', 'brand' => 'C&A', 'price' => 129.00],
            ['name' => 'Blusa Riachuelo Manga Longa Básica Branca', 'category' => 'Moda Feminina', 'brand' => 'Riachuelo', 'price' => 59.90],

            // Casa e Decoração - Design brasileiro
            ['name' => 'Sofá Tok&Stok 3 Lugares Tecido Cinza', 'category' => 'Casa e Decoração', 'brand' => 'Tok&Stok', 'price' => 1899.00],
            ['name' => 'Mesa de Jantar Tok&Stok 6 Cadeiras Madeira', 'category' => 'Casa e Decoração', 'brand' => 'Tok&Stok', 'price' => 1299.00],
            ['name' => 'Conjunto de Panelas Tramontina Alumínio 5 Peças', 'category' => 'Casa e Decoração', 'brand' => 'Tramontina', 'price' => 299.00],
            ['name' => 'Aspirador de Pó Electrolux Sonic SON10', 'category' => 'Casa e Decoração', 'brand' => 'Electrolux', 'price' => 199.00],

            // Esportes e Lazer - Marcas nacionais
            ['name' => 'Bicicleta Caloi Elite Carbon 21 Velocidades', 'category' => 'Esportes e Lazer', 'brand' => 'Caloi', 'price' => 899.00],
            ['name' => 'Esteira Elétrica Movement LX160 Dobrável', 'category' => 'Esportes e Lazer', 'brand' => 'Movement', 'price' => 1599.00],
            ['name' => 'Bola de Futebol Penalty S11 R1 Campo', 'category' => 'Esportes e Lazer', 'brand' => 'Penalty', 'price' => 89.90],
            ['name' => 'Kit Halteres Ajustáveis 20kg Kikos', 'category' => 'Esportes e Lazer', 'brand' => 'Kikos', 'price' => 199.00],

            // Beleza e Cuidados - Cosméticos brasileiros
            ['name' => 'Perfume Natura Essencial Masculino 100ml', 'category' => 'Beleza e Cuidados', 'brand' => 'Natura', 'price' => 159.90],
            ['name' => 'Kit Shampoo + Condicionador L\'Oréal Elseve', 'category' => 'Beleza e Cuidados', 'brand' => 'L\'Oréal', 'price' => 49.90],
            ['name' => 'Creme Facial Antissinais O Boticário 50g', 'category' => 'Beleza e Cuidados', 'brand' => 'O Boticário', 'price' => 79.90],
            ['name' => 'Batom Avon Ultra Color Matte Vermelho', 'category' => 'Beleza e Cuidados', 'brand' => 'Avon', 'price' => 24.90],

            // Livros e Papelaria - Cultura brasileira
            ['name' => 'Dom Casmurro - Machado de Assis (Clássicos)', 'category' => 'Livros e Papelaria', 'brand' => 'Saraiva', 'price' => 29.90],
            ['name' => 'Caderno Universitário Tilibra 200 Folhas', 'category' => 'Livros e Papelaria', 'brand' => 'Tilibra', 'price' => 19.90],
            ['name' => 'Kit Canetas BIC Cristal Azul 50 Unidades', 'category' => 'Livros e Papelaria', 'brand' => 'BIC', 'price' => 39.90],

            // Brinquedos e Games - Entretenimento
            ['name' => 'PlayStation 5 Console 825GB SSD', 'category' => 'Brinquedos e Games', 'brand' => 'Sony', 'price' => 4199.00],
            ['name' => 'Boneca Barbie Fashionista Morena', 'category' => 'Brinquedos e Games', 'brand' => 'Mattel', 'price' => 89.90],
            ['name' => 'LEGO Creator 3 em 1 Dragão Vermelho', 'category' => 'Brinquedos e Games', 'brand' => 'LEGO', 'price' => 199.00],

            // Alimentação - Produtos brasileiros
            ['name' => 'Café Pilão Tradicional Torrado e Moído 500g', 'category' => 'Alimentação', 'brand' => 'Pilão', 'price' => 12.90],
            ['name' => 'Açúcar Cristal União Especial 1kg', 'category' => 'Alimentação', 'brand' => 'União', 'price' => 4.50],
            ['name' => 'Chocolate Nestlé Bis Oreo 126g', 'category' => 'Alimentação', 'brand' => 'Nestlé', 'price' => 8.90],
            ['name' => 'Refrigerante Coca-Cola Original 2L', 'category' => 'Alimentação', 'brand' => 'Coca-Cola', 'price' => 6.99],
        ];

        foreach ($productsData as $productData) {
            $category = $categories->firstWhere('name', $productData['category']);
            $brand = $brands->firstWhere('name', $productData['brand']);
            
            if ($category && $brand) {
                Product::create([
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']),
                    'description' => "Produto de alta qualidade da marca {$productData['brand']} na categoria {$productData['category']}. Ideal para o mercado brasileiro.",
                    'price' => $productData['price'],
                    'sku' => 'BR' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                    'stock_quantity' => rand(10, 150),
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'active' => true,
                ]);
            }
        }
    }
}