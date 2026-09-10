<?php

namespace App\Http\Controllers;

use App\UserFacebook;
use Illuminate\Http\Request;

class UserFacebookController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/api/users/facebook",
     *     description="Store the supplied user.
    the answer will follow the next structure:
    {
    {
    <strong style='color:blue'>id</strong>: HL user id,
    <strong style='color:blue'>email</strong>: user email,
    <strong style='color:blue'>name</strong>: user name,
    <strong style='color:blue'>location</strong>: user locale,
    <strong style='color:blue'>lang</strong>: user lang,
    <strong style='color:blue'>birthday</strong>: user birth date,
    <strong style='color:blue'>gender</strong>: user gender (male or female),
    <strong style='color:blue'>email_result</strong>: evaluation of the validity of the email (Undeliverable, Risky, Deliverable),
    <strong style='color:blue'>unsubscribed</strong>: User subscription to HL notifications 1=yes, 0=no,
    <strong style='color:blue'>user_facebook</strong>: {
    <strong style='color:blue'>name</strong>: user name obtained by facebook,
    <strong style='color:blue'>gender</strong>: user gender obtained by facebook,
    <strong style='color:blue'>age</strong>: user age obtained by facebook,
    <strong style='color:blue'>locationID</strong>: user facebook location id,
    <strong style='color:blue'>locationName</strong>: user location name obtained by facebook,
    <strong style='color:blue'>locale</strong>: user locale obtained by facebook,
    <strong style='color:blue'>facebook_img</strong>: user image url obtained by facebook,
    <strong style='color:blue'>friends</strong>: number of user friends,
    <strong style='color:blue'>birthday</strong>: user birthday obtained by facebook
    }
    }",
     *     tags={"HL User Facebook"},
     *     @SWG\Parameter(
     *         name="id_usuario",
     *         in="query",
     *         type="integer",
     *         description="HL user id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="nombre",
     *         in="query",
     *         type="string",
     *         description="HL user name",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="gender",
     *         in="query",
     *         type="string",
     *         description="HL user gender",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="id_facebook",
     *         in="query",
     *         type="string",
     *         description="Facebook id",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="locationID",
     *         in="query",
     *         type="string",
     *         description="Facebook locationID",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="locationName",
     *         in="query",
     *         type="string",
     *         description="Facebook locationName",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="locale",
     *         in="query",
     *         type="string",
     *         description="Facebook locale",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="facebook_img",
     *         in="query",
     *         type="string",
     *         description="Facebook profile image",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="amigos",
     *         in="query",
     *         type="integer",
     *         description="Facebook user friends",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="query",
     *         type="string",
     *         description="Facebook user email",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="age_max",
     *         in="query",
     *         type="string",
     *         description="Facebook user age_max",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="age_min",
     *         in="query",
     *         type="string",
     *         description="Facebook user age_min",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="birthday",
     *         in="query",
     *         type="string",
     *         description="Facebook user birthday",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="success",
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return UserFacebook::create($request->all());
    }
}
