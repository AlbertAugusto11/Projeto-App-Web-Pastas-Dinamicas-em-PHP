function appPastasDinamicas() {
    document.getElementById("processBtn").addEventListener("click", () => {
        const fileInput = document.getElementById("fileInput").files[0];
        const pedidoTipo = document.getElementById("pedidoTipo").value;

        // ✅ Verificação da extensão
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

            // ✅ Verificação das colunas esperadas
            const colunasEsperadas = ["pasta", "cod", "descr", "qtdCx", "venda"];
            const faltando = colunasEsperadas.filter(c => !cabecalho.includes(c));

            if (faltando.length > 0) {
                alert("Colunas faltando no CSV: " + faltando.join(", "));
                return;
            }

            let resultado = [cabecalho.join(",") + ",segPedido"];

            dados.forEach(linha => {
                const [pasta, cod, descr, qtdCx, venda] = linha.split(",");
                const v1 = parseFloat(venda);
                const qtd = parseFloat(qtdCx);
                const media = v1 / 3;

                const pedidoBaixo = Math.ceil((media * 0.5) / qtd);
                const pedidoNormal = Math.ceil(media / qtd);
                const pedidoAlto = Math.ceil((media * 1.4) / qtd);

                let segPedido;
                if (pedidoTipo === "pedidoBaixo") segPedido = pedidoBaixo;
                else if (pedidoTipo === "pedidoNormal") segPedido = pedidoNormal;
                else segPedido = pedidoAlto;

                resultado.push([pasta, cod, descr, qtdCx, v1, segPedido.toFixed(2)].join(","));
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

// ✅ Como você usa defer, basta chamar direto:
appPastasDinamicas();
