function filtrarProdutos(categoria) {
    const produtos = document.querySelectorAll('.produto-card');
    produtos.forEach(produto => {
        if (categoria === 'todos' || produto.classList.contains(categoria)) {
            produto.style.display = 'block';
        } else {
            produto.style.display = 'none';
        }
    });
}