<?php
class Dijkstra
{
    protected $graph;

    public function __construct($graph) {
        $this->graph = $graph;
    }

    public function shortestPath($source, $target) {
        // массив кратчайших путей к каждому узлу
        $d = [];
        // массив "предшественников" для каждого узла
        $pi = [];
        // очередь всех неоптимизированных узлов
        $Q = new SplPriorityQueue();

        foreach ($this->graph as $v => $adj) {
            $d[$v] = INF; // устанавливаем изначальные расстояния как бесконечность
            $pi[$v] = null; // никаких узлов позади нет
            foreach ($adj as $w => $cost) {
                // воспользуемся ценой связи как приоритетом
                $Q->insert($w, $cost);
            }
        }

        // начальная дистанция на стартовом узле - 0
        $d[$source] = 0;

        while (!$Q->isEmpty()) {
            // извлечем минимальную цену
            $u = $Q->extract();
            if (!empty($this->graph[$u])) {
                // пройдемся по всем соседним узлам
                foreach ($this->graph[$u] as $v => $cost) {
                    // установим новую длину пути для соседнего узла
                    $alt = $d[$u] + $cost;
                    // если он оказался короче
                    if ($alt < $d[$v]) {
                        $d[$v] = $alt; // update minimum length to vertex установим как минимальное расстояние до этого узла
                        $pi[$v] = $u;  // добавим соседа как предшествующий этому узла
                    }
                }
            }
        }

        // теперь мы можем найти минимальный путь
        // используя обратный проход
        $S = new SplStack(); // кратчайший путь как стек
        $u = $target;
        $dist = 0;
        // проход от целевого узла до стартового
        while (isset($pi[$u]) && $pi[$u]) {
            $S->push($u);
            $dist += $this->graph[$u][$pi[$u]]; // добавим дистанцию для предшествующих
            $u = $pi[$u];
        }

        // стек будет пустой, если нет пути назад
        if ($S->isEmpty()) {
            echo "Нет пути из $source в $target";
        } else {
            // добавим стартовый узел и покажем весь путь
            $S->push($source);
            echo "$dist:";
            $sep = '';
            foreach ($S as $v) {
                echo $sep, $v;
                $sep = '->';
            }
            echo "<br/>";
        }
    }
}

$graph =
    [
      'A' => ['B' => 3, 'D' => 3, 'F' => 6],
      'B' => ['A' => 3, 'D' => 1, 'E' => 3],
      'C' => ['E' => 2, 'F' => 3],
      'D' => ['A' => 3, 'B' => 1, 'E' => 1, 'F' => 2],
      'E' => ['B' => 3, 'C' => 2, 'D' => 1, 'F' => 5],
      'F' => ['A' => 6, 'C' => 3, 'D' => 2, 'E' => 5],
    ];

$path = new Dijkstra($graph);
$path->shortestPath('A', 'F');