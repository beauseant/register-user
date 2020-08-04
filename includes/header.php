          <?php              
              if ($_SESSION['tipo'] == 'personal') {
                echo '
                      <li class="nav-item">
                        <a class="nav-link" href="home.php">inicio</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="qrs.php">QRs</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="admin.php">administrar</a>
                      </li>

                ';

              }

              echo '
                  <li class="nav-item">
                      <a class="nav-link" href="logout.php">salir</a>
                  </li>
              ';
          ?>
