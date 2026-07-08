<?php
require_once __DIR__ . '/../../Middleware/auth.php';
verificarAutenticacao();
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fa-solid fa-headset me-2 text-secondary"></i>Atendimentos</h1>
    <button class="btn btn-primary" onclick="abrirModalAtendimento()">
        <i class="fa-solid fa-plus me-1"></i> Novo Atendimento
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Pessoa</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody id="tabela-atendimentos-corpo">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAtendimento" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Atendimento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAtendimento">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pessoa</label>
                        <select name="pessoa_id" id="selectPessoa" class="form-select" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Atendimento</label>
                        <select name="tipo_id" id="selectTipo" class="form-select" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição do Atendimento</label>
                        <textarea name="descricao" class="form-control" rows="3" required></textarea>
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
let modalAtendimentoInstancia = null;

async function abrirModalAtendimento() {
    document.getElementById('formAtendimento').reset();
    
    const respPessoas = await fetch('index.php?controller=pessoas&action=listar_api');
    const pessoas = await respPessoas.json();
    const selectPessoa = document.getElementById('selectPessoa');
    selectPessoa.innerHTML = '<option value="">Selecione...</option>';
    pessoas.forEach(p => selectPessoa.innerHTML += `<option value="${p.id}">${p.nome}</option>`);

    const respTipos = await fetch('index.php?controller=tipos&action=listar_api');
    const tipos = await respTipos.json();
    const selectTipo = document.getElementById('selectTipo');
    selectTipo.innerHTML = '<option value="">Selecione...</option>';
    tipos.filter(t => t.status === 'ativo').forEach(t => selectTipo.innerHTML += `<option value="${t.id}">${t.nome}</option>`);

    if(!modalAtendimentoInstancia) {
        modalAtendimentoInstancia = new bootstrap.Modal(document.getElementById('modalAtendimento'));
    }
    modalAtendimentoInstancia.show();
}

async function carregarAtendimentos() {
    const tbody = document.getElementById('tabela-atendimentos-corpo');
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">Carregando...</td></tr>';
    try {
        const resposta = await fetch('index.php?controller=atendimentos&action=listar_api');
        const dados = await resposta.json();
        tbody.innerHTML = '';
        if (dados.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Nenhum atendimento registrado.</td></tr>';
            return;
        }
        dados.forEach(a => {
            const dataFormatada = new Date(a.criado_em).toLocaleString('pt-BR');
            tbody.innerHTML += `
                <tr>
                    <td>${a.id}</td>
                    <td>${dataFormatada}</td>
                    <td>${a.pessoa_nome}</td>
                    <td>${a.tipo_nome}</td>
                    <td>${a.descricao}</td>
                </tr>
            `;
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erro ao carregar dados.</td></tr>';
    }
}

document.getElementById('formAtendimento').addEventListener('submit', async function(e) {
    e.preventDefault();
    try {
        const dados = new FormData(this);
        const resposta = await fetch('index.php?controller=atendimentos&action=criar', {
            method: 'POST',
            body: dados
        });
        const resultado = await resposta.json();
        if(resultado.erro) {
            alert(resultado.erro);
        } else {
            modalAtendimentoInstancia.hide();
            await carregarAtendimentos();
        }
    } catch (error) {
        alert('Erro ao salvar o registro.');
    }
});

document.addEventListener('DOMContentLoaded', carregarAtendimentos);
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
