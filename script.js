let listaP = [];

function getSelectedFolders() {
    const allSelected = document.getElementById("allFolders").checked;
    if (allSelected) {
        return [1, 2, 3, 4]; // todas as pastas
    }
    const checkboxes = document.querySelectorAll('.folder-check:checked');
    return Array.from(checkboxes).map(cb => parseInt(cb.value));
}

function toggleCheckboxes() {
    const allSelected = document.getElementById("allFolders").checked;
    const checkboxes = document.querySelectorAll('.folder-check');

    checkboxes.forEach(cb => {
        cb.disabled = allSelected;
        if (allSelected) cb.checked = false;
    });
}

function appPastasDinamicas() {
    document.getElementById("allFolders").addEventListener("change", toggleCheckboxes);

    document.getElementById("processBtn").addEventListener("click", () => {
        const fileInput = document.getElementById("fileInput").files[0];
        const pedidoTipo = document.getElementById("pedidoTipo").value;

        if (!fileInput) {
            alert("Por favor, selecione um arquivo CSV.");
            return;
        }
        if (!fileInput.name.toLowerCase().endsWith(".csv")) {
            alert("O arquivo selecionado não é um CSV válido.");
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const linhas = e.target.result.split("\n").map(l => l.trim()).filter(l => l);
            const cabecalho = linhas[0].split(",").map(c => c.trim());
            const dados = linhas.slice(1);

            const colunasEsperadas = ["pasta", "cod", "descr", "qtdCx", "venda"];
            const faltando = colunasEsperadas.filter(c => !cabecalho.includes(c));

            if (faltando.length > 0) {
                alert("Colunas faltando no CSV: " + faltando.join(", "));
                return;
            }

            listaP = []; // limpa antes de processar
            const pastasSelecionadas = getSelectedFolders();

            dados.forEach(linha => {
                const [pasta, cod, descr, qtdCx, venda] = linha.split(",");
                const pastaNum = parseInt(pasta);

                if (!pastasSelecionadas.includes(pastaNum)) return;

                const v1 = parseFloat(venda);
                const qtd = parseFloat(qtdCx);
                const media = v1 / 3;

                let obj = { 
                    pasta: pastaNum, 
                    cod: parseInt(cod), 
                    descr: descr, 
                    qtdCx: qtd, 
                    venda: v1, 
                    pedidoBaixo: Math.ceil((media * 0.4) / qtd), 
                    pedidoNormal: Math.ceil(media / qtd), 
                    pedidoAlto: Math.ceil((media * 1.5) / qtd)
                };
                listaP.push(obj);
            });

            // ✅ Ordena os objetos pela pasta
            listaP.sort((a, b) => a.pasta - b.pasta);

            // ✅ Monta o CSV já ordenado
            let resultado = [cabecalho.join(",") + ",segPedido"];
            listaP.forEach(obj => {
                let segPedido;
                if (pedidoTipo === "pedidoBaixo") segPedido = obj.pedidoBaixo;
                else if (pedidoTipo === "pedidoNormal") segPedido = obj.pedidoNormal;
                else segPedido = obj.pedidoAlto;

                resultado.push([
                    obj.pasta, 
                    obj.cod, 
                    obj.descr, 
                    obj.qtdCx, 
                    obj.venda, 
                    segPedido.toFixed(2)
                ].join(","));
            });

            const blob = new Blob([resultado.join("\n")], { type: "text/csv" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "resultado.csv";
            a.click();
        };

        reader.readAsText(fileInput);
    });
}

appPastasDinamicas();
