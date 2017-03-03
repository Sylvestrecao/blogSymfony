<?php

namespace BlogBundle\Controller;

use BlogBundle\BlogBundle;
use BlogBundle\Entity\Post;
use BlogBundle\Form\CommentType;
use BlogBundle\Form\PostType;
use BlogBundle\Entity\User;
use BlogBundle\Entity\Category;
use BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Post controller.
 *
 * @Route("/")
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('BlogBundle:Post')->getPostWithCategoryAndUserQuery();
        $posts = $this->get('knp_paginator')->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('BlogBundle:Default:index.html.twig', array(
            'posts' => $posts,
        ));

    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/{id}", name="post_show", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $post = $em->getRepository('BlogBundle:Post')->getOnePostWithCategoryAndUserAndComment($id);

        return $this->render('BlogBundle:Default:show.html.twig', array(
            'post' => $post,
        ));
    }

    /**
     *
     * @Route("/{id}/add_comment", name="add_comment", requirements={"id": "\d+"})
     * @Method("POST")
     */
    public function addCommentAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        // Add comment
        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment, array(
            'action' => $this->generateUrl('add_comment', array('id' => $post->getId())),
            'method' => 'POST',
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $comment->setPost($post);

            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('BlogBundle:Default:add-comment.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     *
     *
     * @Route("/{id}/add_comment/{comment_parent_id}", name="add_comment_response", requirements={"id": "\d+"})
     * @Method("POST")
     */
    public function addCommentResponseAction(Request $request, Post $post, $comment_parent_id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentParent = $em->getRepository('BlogBundle:Comment')->find($comment_parent_id);
        // Add comment
        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment, array(
            'action' => $this->generateUrl('add_comment_response', array('id' => $post->getId(), 'comment_parent_id' => $comment_parent_id)),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $comment->setPost($post);
            $comment->setParent($commentParent);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Votre réponse a été ajouté avec succès !');

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('BlogBundle:Default:add-comment.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays author post entity.
     *
     * @Route("/author/{id}", name="author_show", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function authorAction(User $user, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->getAuthorPosts($id);
        
        return $this->render('BlogBundle:Default:show-author-post.html.twig', array(
            'author' => $user,
            'posts'  => $posts
        ));
    }

    /**
     * Finds and displays category post entity.
     *
     * @Route("/category/{id}", name="category_show", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function categoryAction(Category $category, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->getCategoryPosts($id);

        return $this->render('BlogBundle:Default:show-category-post.html.twig', array(
            'category' => $category,
            'posts'    => $posts
        ));
    }


    /**
     * Displays admin panel.
     *
     * @Route("/admin", name="admin_show")
     * @Method("GET")
     */
    public function adminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->getPostWithCategoryAndUser();
        $form = $this->get('form.factory')->create();

        return $this->render('BlogBundle:Admin:admin-index.html.twig', array(
            'posts' => $posts,
            'form'  => $form->createview(),
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/admin/new", name="admin_post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->get('form.factory')->create(PostType::class, $post, array('user' => $this->getUser()->getUsername()));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Votre article a été ajouté avec succès !');

            return $this->redirectToRoute('admin_show');
        }

        return $this->render('BlogBundle:Admin:admin-new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/admin/edit/{id}", name="admin_post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('BlogBundle:Post')->getOnePostWithCategoryAndUserAndComment($id);
        $commentReport = $em->getRepository('BlogBundle:Comment')->getCommentReport($id);

        $form = $this->createForm(PostType::class, $post, array('user' => $this->getUser()->getUsername()));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Votre article a été modifié avec succès !');

            return $this->redirectToRoute('admin_show');
        }

        return $this->render('BlogBundle:Admin:admin-edit.html.twig', array(
            'form' => $form->createView(),
            'post' => $post,
            'commentReport' => $commentReport
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/admin/delete", name="admin_post_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('POST_ID');

        $post = $em->getRepository('BlogBundle:Post')->find($id);
        $em->remove($post);
        $this->addFlash('success', 'Votre article a été supprimé avec succès !');
        $em->flush($post);
        
        return $this->redirectToRoute('admin_show');
    }

    /**
     * Deletes a comment entity.
     *
     * @Route("/admin/delete-comment", options={"expose"=true}, name="admin_comment_delete")
     * @Method("POST")
     */
    public function deleteCommentAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            $id = $data['Comment_ID'];
        }

        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('BlogBundle:Comment')->find($id);
        $em->remove($comment);
        $em->flush();

        return new Response('success');
    }

    /**
     * Set reportComment bool
     *
     * @Route("/setreport", options={"expose"=true}, name="set_report")
     * @Method("POST")
     */
    public function setReportAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $data = $request->request->all();
            $id = $data['Comment_ID'];
           // $session = $request->getSession();
           // $session->set('panier', $data);
        }

        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('BlogBundle:Comment')->find($id);
        $reportNumber = $comment->getReport();
        $newReportNumber = $reportNumber + 1;
        $comment->setReport($newReportNumber);

        $em->persist($comment);
        $em->flush();

        return new Response('success');
    }
}
