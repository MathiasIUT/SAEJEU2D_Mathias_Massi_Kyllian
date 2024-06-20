package universite_paris8.iut.mcontay.saejeu2d.modele;

import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

public class EnvironnementTest {
    private Environnement environnement;

    @BeforeEach
    public void setUp() {
        // Initialisation de l'environnement
        environnement = new Environnement();
    }

    @Test
    public void testInitialisationEnvironnement() {
        // Vérifie que les entités de l'environnement sont initialisées correctement
        assertNotNull(environnement.getJoueur(), "Le joueur doit être initialisé");
        assertNotNull(environnement.getMonstre(), "Le monstre doit être initialisé");
        assertNotNull(environnement.getSkateur(), "Le skateur doit être initialisé");
    }

    @Test
    public void testAjouterEntite() {
        // Teste l'ajout d'une entité dans l'environnement
        Joueur joueurTest = new Joueur(environnement, environnement.getTerrain(), "TestJoueur", 100, 10, 5, 0, 0, 1, 5);
        environnement.ajouterEntite(joueurTest);
        assertTrue(environnement.getListeActeurs().contains(joueurTest), "L'entité joueurTest devrait être ajoutée à la liste des acteurs");
    }

    @Test
    public void testSupprimerEntiteParId() {
        // Teste la suppression d'une entité par son ID
        int idMonstre = environnement.getMonstre().getId();
        environnement.supprimerEntiteParId(idMonstre);
        assertFalse(environnement.getListeActeurs().contains(environnement.getMonstre()), "Le monstre ne devrait plus être dans la liste des acteurs après suppression");
    }

    @Test
    public void testFaireUnTour() {
        // Teste l'exécution d'un tour de jeu
        assertTrue(environnement.faireUnTour(), "La méthode faireUnTour devrait retourner true si le joueur est vivant");
        environnement.getJoueur().setPtsDeVie(0);
        assertFalse(environnement.faireUnTour(), "La méthode faireUnTour devrait retourner false si le joueur est mort");
    }
}
