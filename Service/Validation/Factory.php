<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */

namespace TIG\PostNL\Service\Validation;

use TIG\PostNL\Exception as PostNLException;

class Factory
{
    /**
     * @var Contract[]
     */
    private $validators;

    /**
     * Factory constructor.
     *
     * @param Contract[] $validators
     */
    public function __construct(
        $validators = []
    ) {
        foreach ($validators as $validator) {
            $this->checkImplementation($validator);
        }

        $this->validators = $validators;
    }

    /**
     * @param $validator
     *
     * @throws PostNLException
     */
    private function checkImplementation($validator)
    {
        $implementations = class_implements($validator);

        if (!array_key_exists(Contract::class, $implementations)) {
            throw new PostNLException(__('Class is not an implementation of ' . Contract::class));
        }
    }

    /**
     * @param $type
     * @param $value
     *
     * @return bool|mixed
     * @throws PostNLException
     */
    public function validate($type, $value)
    {
        switch ($type) {
            case 'price':
            case 'weight':
            case 'subtotal':
            case 'quantity':
                return $this->validators['decimal']->validate($value);

            case 'parcel-type':
                return $this->validators['parcelType']->validate($value);

            case 'country':
                return $this->validators['country']->validate($value);

            case 'region':
                return $this->validators['region']->validate($value);
        }

        throw new PostNLException(__('There is no implementation found for the "' . $type . '" validator'));
    }
}
