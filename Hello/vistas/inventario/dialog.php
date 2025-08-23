<button class="text-red-500 hover:text-red-600 mr-3" onclick="window.mydialog.showModal();">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                            <dialog id="mydialog">
                                                                <p>Introduzca la contraseña para eliminar el item</p>
                                                                <form action="delete_provee.php" method="POST">
                                                                    <input type="password" name="clave">
                                                                    <input type="hidden" name="id" value="<?php echo $prove['id']?>">
                                                                    <input type="submit" name="send" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-500 text-base font-medium text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm" value="Enviar"></input>
                                                                </form>
                                                                <button onclick='window.mydialog.close();'>Cerrar modal</button>
                                                            </dialog>