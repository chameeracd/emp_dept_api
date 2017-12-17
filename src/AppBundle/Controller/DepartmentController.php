<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Department;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Department controller.
 *
 * @Route("department")
 */
class DepartmentController extends FOSRestController
{
    /**
     * Lists all department entities.
     *
     * @ApiDoc()
     *
     * @Route("/", name="department_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AppBundle:Department');
        $arrayTree = $repo->childrenHierarchy();

        $view = $this->view($arrayTree, 200);

        return $this->handleView($view);
    }

    /**
     * Creates a new department.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="Create a new department",
     *  input="AppBundle\Form\DepartmentType",
     *  output="AppBundle\Entity\Department"
     * )
     *
     * @Route("/", name="department_new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $department = new Department();
        $form = $this->createForm('AppBundle\Form\DepartmentType', $department);
        $form->handleRequest($request);
        $view = new View($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();

            $view = $this->view($department, 200);

            return $this->handleView($view);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Finds and displays a department entity.
     *
     * @Route("/{id}", name="department_show")
     * @Method("GET")
     */
    public function showAction(Department $department)
    {
        $deleteForm = $this->createDeleteForm($department);

        return $this->render('department/show.html.twig', array(
            'department' => $department,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a department entity.
     *
     * @param Department $department The department entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Department $department)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('department_delete', array('id' => $department->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing department entity.
     *
     * @Route("/{id}/edit", name="department_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Department $department)
    {
        $deleteForm = $this->createDeleteForm($department);
        $editForm = $this->createForm('AppBundle\Form\DepartmentType', $department);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('department_edit', array('id' => $department->getId()));
        }

        return $this->render('department/edit.html.twig', array(
            'department' => $department,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a department entity.
     *
     * @Route("/{id}", name="department_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Department $department)
    {
        $form = $this->createDeleteForm($department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($department);
            $em->flush();
        }

        return $this->redirectToRoute('department_index');
    }
}
