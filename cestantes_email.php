<?php  
  require  "common.inc.php"; 
  verifica_seguranca($_SESSION[PAP_RESP_PEDIDO]);
  top();
?>



<form class="form-inline" action="cestantes_email.php" method="post" name="frm_filtro" id="frm_filtro">
	<legend>Email dos Cestantes para Comunicação</legend> 
	<?php  
  		$usr_archive = request_get("usr_archive",0);  		
		$usr_nuc = request_get("usr_nuc",($_SESSION[PAP_RESP_NUCLEO]? $_SESSION['usr.nuc'] : -1)) ;
	
		
	?>

    
     <fieldset>
  
     
  				<label for="usr_archive">Situação: </label>            
                 <select name="usr_archive" id="usr_archive" onchange="javascript:frm_filtro.submit();" class="input-medium">
                        <option value="-1" <?php echo( ($usr_archive)==-1?" selected" : ""); ?> >TODOS</option>
                        <option value="0"  <?php echo( ($usr_archive)==0?" selected" : ""); ?> >Ativos</option>
                        <option value="1"  <?php echo( ($usr_archive)==1?" selected" : ""); ?> >Inativos</option>            
                 </select>    
                 
                 &nbsp;
                    
  				<label for="usr_nuc">Núcleo: </label>            
                <select name="usr_nuc" id="usr_nuc" onchange="javascript:frm_filtro.submit();" class="input-medium">
                    <option value="-1" <?php echo( ($usr_nuc)==-1?" selected" : ""); ?> >TODOS</option>
                    <option value="-1">-------------</option>                     
                    <?php
                        
                        $sql = "SELECT nuc_id, nuc_nome_curto, nuc_archive ";
                        $sql.= "FROM nucleos ";
                        $sql.= "ORDER BY nuc_archive, nuc_nome_curto ";
                        $res = executa_sql($sql);
                        if($res)
                        {
						  $arquivados=0;
                          while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)) 
                          {
							 if(!$arquivados)
							 {
								 if($row["nuc_archive"]==1) 
								 {
									 echo("<option value='-1'>-------------</option>");									 
									 $arquivados=1;
								 }
							 }
                             echo("<option value='" . $row['nuc_id'] . "'");
                             if($row['nuc_id']==$usr_nuc) echo(" selected");
                             echo (">" . $row['nuc_nome_curto'] . "</option>");
                          }
                        }
                    ?>                        
                </select>                           
                  
     </fieldset>
</form>


    Lista de Destinatários:<br>
       <form>
  
				<?php
					
					$sql = "SELECT usr_email, usr_email_alternativo ";
					$sql.= "FROM usuarios LEFT JOIN nucleos ON usr_nuc = nuc_id ";	
					$sql.= "WHERE 1 ";
					if($usr_archive!=-1) $sql.= " AND usr_archive = ' " . $usr_archive .  " ' ";
					if($usr_nuc!=-1) 	 $sql.= " AND usr_nuc = '" . $usr_nuc .  "' ";						
					$sql.= "ORDER BY nuc_nome_curto ";
								
					$res = executa_sql($sql);
					$contador = 0;
				    if($res)
					{
					  echo("<textarea rows='40' class='span10'>");
					 while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)) 
				     {
						 echo($row["usr_email"] . ", ");
						 if(strlen($row["usr_email_alternativo"])> 3)
						 {
							 echo($row["usr_email_alternativo"] . ", ");
						 }						 
				     }
				    	echo("</textarea>");
				   }
				?>
                
	</form>


<?php 
 
	footer();
?>