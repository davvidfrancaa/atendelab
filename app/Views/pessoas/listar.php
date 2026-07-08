<?php
require_once __DIR__ . '/../../Middleware/auth.php';
verificarAutenticacao();
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-users me-2 text-secondary"></i>Gerenciamento de Pessoas</h1>
    <button class="btn btn-primary" onclick="abrirModalPessoa()">
        <i class="fa-solid fa-plus me-1"></i> Nova Pessoa
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-pessoas-corpo">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPessoa" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cadastrar Pessoa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPessoa">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modalPessoaInstancia = null;

function abrirModalPessoa() {
    document.getElementById('formPessoa').reset();
    if(!modalPessoaInstancia) {
        modalPessoaInstancia = new bootstrap.Modal(document.getElementById('modalPessoa'));
    }
    modalPessoaInstancia.show();
}

async function carregarPessoas() {
    const tbody = document.getElementById('tabela-pessoas-corpo');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Carregando...</td></tr>';
    try {
        const resposta = await fetch('index.php?controller=pessoas&action=listar_api');
        const pessoas = await resposta.json();
        tbody.innerHTML = '';
        if (pessoas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Nenhuma pessoa cadastrada.</td></tr>';
            return;
        }
        pessoas.forEach(p => {
            tbody.innerHTML += `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.nome}</td>
                    <td>${p.email}</td>
                    <td>${p.telefone ?? ''}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger" onclick="excluirPessoa(${p.id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erro ao carregar dados.</td></tr>';
    }
}

document.getElementById('formPessoa').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const dados = new FormData(this);
        const resposta = await fetch('index.php?controller=pessoas&action=criar', {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if(resultado.erro) {
            alert(resultado.erro);
        } else {
            modalPessoaInstancia.hide();
            await carregarPessoas();
        }
    } catch (error) {
        alert('Erro ao salvar o registro.');
    }
});

async function excluirPessoa(id) {
    if(!confirm('Deseja realmente remover esta pessoa?')) return;
    try {
        const dados = new URLSearchParams();
        dados.append('id', id);
        await fetch('index.php?controller=pessoas&action=excluir', {
            method: 'POST',
            body: dados
        });
        await carregarPessoas();
    } catch (error) {
        alert('Erro ao excluir o registro.');
    }
}

document.addEventListener('DOMContentLoaded', carregarPessoas);
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>