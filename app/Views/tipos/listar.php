<?php
require_once __DIR__ . '/../../Middleware/auth.php';
verificarAutenticacao();
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-tags me-2 text-secondary"></i>Tipos de Atendimento</h1>
    <button class="btn btn-primary" onclick="abrirModalCadastro()">
        <i class="fa-solid fa-plus me-1"></i> Novo Tipo
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome do Tipo</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-tipos-corpo">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTipo" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTipoTitulo">Novo Tipo de Atendimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTipo">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome do Tipo</label>
                        <input type="text" name="nome" id="tipoNome" class="form-control" required>
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
let modalInstancia = null;

function abrirModalCadastro() {
    document.getElementById('formTipo').reset();
    document.getElementById('modalTipoTitulo').innerText = 'Novo Tipo de Atendimento';
    if(!modalInstancia) {
        modalInstancia = new bootstrap.Modal(document.getElementById('modalTipo'));
    }
    modalInstancia.show();
}

async function carregarTipos() {
    const tbody = document.getElementById('tabela-tipos-corpo');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center">Carregando...</td></tr>';
    try {
        const resposta = await fetch('index.php?controller=tipos&action=listar_api');
        const tipos = await resposta.json();
        tbody.innerHTML = '';
        if (tipos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Nenhum tipo cadastrado.</td></tr>';
            return;
        }
        tipos.forEach(tipo => {
            const statusBadge = tipo.status === 'ativo' 
                ? '<span class="badge bg-success">Ativo</span>' 
                : '<span class="badge bg-danger">Inativo</span>';
            tbody.innerHTML += `
                <tr>
                    <td>${tipo.id}</td>
                    <td>${tipo.nome}</td>
                    <td>${statusBadge}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger" onclick="inativarTipo(${tipo.id})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Erro ao carregar dados.</td></tr>';
    }
}

document.getElementById('formTipo').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const dados = new FormData(this);
        const resposta = await fetch('index.php?controller=tipos&action=criar', {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if(resultado.erro) {
            alert(resultado.erro);
        } else {
            modalInstancia.hide();
            await carregarTipos();
        }
    } catch (error) {
        alert('Erro ao salvar o registro.');
    }
});

async function inativarTipo(id) {
    if(!confirm('Deseja realmente inativar este tipo?')) return;
    try {
        const dados = new URLSearchParams();
        dados.append('id', id);
        await fetch('index.php?controller=tipos&action=inativar', {
            method: 'POST',
            body: dados
        });
        await carregarTipos();
    } catch (error) {
        alert('Erro ao inativar o registro.');
    }
}

document.addEventListener('DOMContentLoaded', carregarTipos);
</script>

<?php 
require __DIR__ . '/../layouts/footer.php'; 
?>