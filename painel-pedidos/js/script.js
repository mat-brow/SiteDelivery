document.addEventListener('DOMContentLoaded', () => {

    // Contadores animados no resumo
    document.querySelectorAll('[data-count]').forEach(el => {
        const alvo = parseFloat(el.dataset.count);
        const emMoeda = el.hasAttribute('data-moeda');
        let inicio = null;
        const passo = t => {
            if (!inicio) inicio = t;
            const p = Math.min((t - inicio) / 900, 1);
            const v = alvo * (1 - Math.pow(1 - p, 3));
            el.textContent = emMoeda
                ? v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                : Math.round(v);
            if (p < 1) requestAnimationFrame(passo);
        };
        requestAnimationFrame(passo);
    });

    // Modal de exclusão: preenche id e nome do cliente
    const modal = document.getElementById('modalExcluir');
    if (modal) {
        modal.addEventListener('show.bs.modal', ev => {
            const b = ev.relatedTarget;
            modal.querySelector('[name=id]').value = b.dataset.id;
            modal.querySelector('#nomeExcluir').textContent = b.dataset.cliente;
        });
    }

    // Mensagem de confirmação (toast)
    document.querySelectorAll('.toast').forEach(t => new bootstrap.Toast(t, { delay: 3000 }).show());

    // Filtro de status aplica ao mudar
    document.querySelectorAll('[data-autosubmit]').forEach(el =>
        el.addEventListener('change', () => el.form.submit()));

    // Validação dos formulários
    document.querySelectorAll('.needs-validation').forEach(f =>
        f.addEventListener('submit', e => {
            if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
            f.classList.add('was-validated');
        }));
});
