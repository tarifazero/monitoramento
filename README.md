## Cadê Meu Busão - Monitoramento da Oferta de Ônibus no Transporte Coletivo de Belo Horizonte

Este repositório contém código elaborado para a ferramenta [Cadê Meu Busão](https://tarifazerobh.org/cade-meu-busao/).

Cadê Meu Busão é uma ferramenta que busca dar mais transparência aos dados relacionados à oferta do transporte coletivo por ônibus em Belo Horizonte. Todo o código é aberto e de livre uso. As informações consideradas são disponibilizadas pela [Prefeitura de Belo Horizonte](https://prefeitura.pbh.gov.br/) no Portal de [Dados Abertos da BHTRANS](https://dados.pbh.gov.br/organization/bhtrans).

No painel de frota de ônibus é possível acompanhar diversas informações: o total da frota ativa do município; quantos ônibus de cada linha estão circulando em tempo real; a média diária de circulação dos veículos nos últimos 90 dias; e o percentual de utilização da frota total, por faixa de hora, no último dia.

As informações em tempo real são extraídas do [Tempo Real Ônibus - Coordenada atualizada](https://dados.pbh.gov.br/dataset/tempo_real_onibus_-_coordenada), que contém as localizações dos ônibus em circulação na cidade, atualizadas a cada 20 segundos. Já o total da frota ativa é calculado a partir da quantidade total de veículos identificada no mês anterior.
