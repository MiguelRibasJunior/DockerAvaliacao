<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro de Pessoas</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: #f5f5f5;
      color: #333;
      font-family: Arial, sans-serif;
      font-size: 14px;
      padding: 30px 16px;
    }

    .container { max-width: 820px; margin: 0 auto; }

    h1 { font-size: 22px; font-weight: bold; margin-bottom: 4px; color: #222; }
    .subtitle { color: #777; font-size: 13px; margin-bottom: 24px; }

    .stats { display: flex; gap: 12px; margin-bottom: 20px; }
    .stat-box { background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 12px 16px; flex: 1; }
    .stat-box strong { display: block; font-size: 20px; color: #2255cc; }
    .stat-box span { font-size: 12px; color: #888; }

    .section { background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 20px; margin-bottom: 20px; }
    .section h2 { font-size: 14px; font-weight: bold; margin-bottom: 14px; color: #444; border-bottom: 1px solid #eee; padding-bottom: 8px; }

    .form-row { display: flex; gap: 12px; margin-bottom: 10px; flex-wrap: wrap; }
    .form-group { display: flex; flex-direction: column; gap: 4px; flex: 1; min-width: 160px; }
    .form-group.wide { flex: 2; }

    label { font-size: 12px; color: #555; }

    input[type="text"] {
      border: 1px solid #ccc;
      border-radius: 3px;
      padding: 7px 10px;
      font-size: 13px;
      font-family: Arial, sans-serif;
      color: #333;
      outline: none;
      width: 100%;
    }
    input[type="text"]:focus { border-color: #2255cc; }

    button.btn-submit {
      background: #2255cc;
      color: #fff;
      border: none;
      border-radius: 3px;
      padding: 8px 20px;
      font-size: 13px;
      cursor: pointer;
      margin-top: 6px;
    }
    button.btn-submit:hover { background: #1a44aa; }

    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    thead th { text-align: left; font-size: 12px; color: #666; font-weight: bold; padding: 6px 10px; border-bottom: 2px solid #ddd; background: #fafafa; }
    tbody tr { border-bottom: 1px solid #eee; }
    tbody tr:hover { background: #f9f9f9; }
    tbody td { padding: 8px 10px; color: #333; }
    tbody td.id-col { color: #aaa; font-size: 12px; }

    .btn-remove {
      background: none;
      border: 1px solid #ccc;
      border-radius: 3px;
      padding: 3px 10px;
      font-size: 12px;
      color: #c00;
      cursor: pointer;
    }
    .btn-remove:hover { background: #fff0f0; border-color: #c00; }

    .empty-msg { text-align: center; padding: 30px; color: #aaa; font-size: 13px; }

    #msg { display: none; padding: 8px 14px; border-radius: 3px; font-size: 13px; margin-bottom: 14px; }
    #msg.ok { background: #eaf4ea; border: 1px solid #aad4aa; color: #2a6f2a; display: block; }
    #msg.err { background: #fdecea; border: 1px solid #f5b8b8; color: #a00; display: block; }

    @media (max-width: 560px) {
      .stats { flex-direction: column; }
      .form-row { flex-direction: column; }
    }
  </style>
</head>
<body>
<div class="container">

  <h1>Cadastro de Pessoas</h1>
  <p class="subtitle">Gerencie nome, sobrenome e endereço com persistência em banco de dados.</p>

  <div class="stats">
    <div class="stat-box">
      <strong id="total-count">—</strong>
      <span>Pessoas cadastradas</span>
    </div>
    <div class="stat-box">
      <strong id="last-name">—</strong>
      <span>Último cadastro</span>
    </div>
  </div>

  <div class="section">
    <h2>Novo Cadastro</h2>
    <div id="msg"></div>
    <div class="form-row">
      <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" id="nome" placeholder="Nome" />
      </div>
      <div class="form-group">
        <label for="sobrenome">Sobrenome</label>
        <input type="text" id="sobrenome" placeholder="Sobrenome" />
      </div>
      <div class="form-group wide">
        <label for="endereco">Endereço</label>
        <input type="text" id="endereco" placeholder="Rua, número – Cidade, UF" />
      </div>
    </div>
    <button class="btn-submit" onclick="cadastrar()">Cadastrar</button>
  </div>

  <div class="section">
    <h2>Pessoas Cadastradas</h2>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Sobrenome</th>
          <th>Endereço</th>
          <th>Cadastrado em</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="tabela-body">
        <tr><td colspan="6"><div class="empty-msg">Carregando...</div></td></tr>
      </tbody>
    </table>
  </div>

</div>

<script>
  const API = 'api.php';

  async function listar() {
    try {
      const res = await fetch(`${API}?action=listar`);
      const pessoas = await res.json();
      const tbody = document.getElementById('tabela-body');

      document.getElementById('total-count').textContent = pessoas.length;
      document.getElementById('last-name').textContent =
        pessoas.length > 0 ? pessoas[0].nome : '—';

      if (pessoas.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6"><div class="empty-msg">Nenhuma pessoa cadastrada ainda.</div></td></tr>`;
        return;
      }

      tbody.innerHTML = pessoas.map(p => `
        <tr>
          <td class="id-col">${p.id}</td>
          <td>${esc(p.nome)}</td>
          <td>${esc(p.sobrenome)}</td>
          <td>${esc(p.endereco)}</td>
          <td>${formatDate(p.criado_em)}</td>
          <td><button class="btn-remove" onclick="deletar(${p.id})">Remover</button></td>
        </tr>
      `).join('');
    } catch (e) {
      showMsg('Erro ao carregar dados.', false);
    }
  }

  async function cadastrar() {
    const nome      = document.getElementById('nome').value.trim();
    const sobrenome = document.getElementById('sobrenome').value.trim();
    const endereco  = document.getElementById('endereco').value.trim();

    if (!nome || !sobrenome || !endereco) {
      showMsg('Preencha todos os campos.', false);
      return;
    }

    try {
      const res = await fetch(`${API}?action=cadastrar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nome, sobrenome, endereco })
      });
      const data = await res.json();

      if (data.sucesso) {
        showMsg('Pessoa cadastrada com sucesso!', true);
        document.getElementById('nome').value = '';
        document.getElementById('sobrenome').value = '';
        document.getElementById('endereco').value = '';
        listar();
      } else {
        showMsg(data.erro || 'Erro ao cadastrar.', false);
      }
    } catch (e) {
      showMsg('Erro de comunicação com o servidor.', false);
    }
  }

  async function deletar(id) {
    if (!confirm('Remover este cadastro?')) return;
    try {
      const res = await fetch(`${API}?action=deletar&id=${id}`, { method: 'DELETE' });
      const data = await res.json();
      if (data.sucesso) {
        showMsg('Registro removido.', true);
        listar();
      }
    } catch (e) {
      showMsg('Erro ao remover.', false);
    }
  }

  function showMsg(txt, ok) {
    const el = document.getElementById('msg');
    el.textContent = txt;
    el.className = ok ? 'ok' : 'err';
    setTimeout(() => { el.className = ''; }, 3000);
  }

  function esc(str) {
    return String(str)
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;');
  }

  function formatDate(str) {
    if (!str) return '—';
    const d = new Date(str);
    return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR', {hour:'2-digit',minute:'2-digit'});
  }

  listar();
</script>
</body>
</html>
