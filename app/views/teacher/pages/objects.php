   <div id="objects">
        <div class="wrapper">
            <table>
                <tr>
                    <th>predmeti</th>
                </tr>
                    <?php foreach($this->data['subjects'] as $subject): ?>
                        <tr>
                            <td><?php echo $subject['name']; ?></td>
                        </tr>
                    <?php endforeach; ?>
            </table>
        </div><!-- end .wrapper -->
    </div><!-- end #objects -->